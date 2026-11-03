<?php

namespace App\Http\Controllers;

use App\Models\Diagnostic;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create()
    {
        return view('booking');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_type' => 'required|in:Laptop,PC Desktop',
            'device_brand' => 'required|string|max:80',
            'device_model' => 'required|string|max:120',
            'issue_chronology' => 'required|string',
            'delivery_method' => 'required|in:Diantar Sendiri,Ambil oleh Teknisi',
            'pickup_address' => 'nullable|string',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:100',
            'photo' => 'nullable|image|max:5120',
            'addon_cleaning' => 'nullable|boolean',
            'addon_repasta' => 'nullable|boolean',
        ]);

        // Cari atau buat akun guest customer tanpa tabrakan email
        $customer = User::where('phone', $validated['customer_phone'])->first();
        if (!$customer && !empty($validated['customer_email'])) {
            $customer = User::where('email', $validated['customer_email'])->first();
        }

        if (!$customer) {
            $email = $validated['customer_email'] ?: ($validated['customer_phone'] . '@guest.sercomp.id');
            if (User::where('email', $email)->exists()) {
                $email = $validated['customer_phone'] . '_' . time() . '@guest.sercomp.id';
            }
            $customer = User::create([
                'name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'],
                'email' => $email,
                'password' => bcrypt(Str::random(16)),
                'role' => 'customer',
            ]);
        } else {
            $customer->update(['name' => $validated['customer_name']]);
        }

        $pickupAddress = $validated['pickup_address'] ?? null;
        if ($validated['delivery_method'] === 'Ambil oleh Teknisi' && empty($pickupAddress)) {
            $pickupAddress = 'Alamat penjemputan akan dikonfirmasi langsung oleh teknisi via WhatsApp.';
        }

        // Generate nomor tiket SC-YYYY-XXXX
        do {
            $ticketNo = 'SC-' . date('Y') . '-' . mt_rand(1000, 9999);
        } while (ServiceOrder::where('ticket_no', $ticketNo)->exists());

        // Generate 4-digit kode PIN rahasia acak untuk diingat dan diverifikasi konsumen
        $securityPin = (string) mt_rand(1000, 9999);

        // Simpan foto kerusakan awal jika diunggah
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $photoUrl = $request->file('photo')->store('issues', 'public');
        }

        $addonCleaning = $request->boolean('addon_cleaning');
        $addonRepasta = $request->boolean('addon_repasta');
        $costCleaning = $addonCleaning ? 50000 : 0;
        $costRepasta = $addonRepasta ? 75000 : 0;
        $totalInitialCost = $costCleaning + $costRepasta;

        // Buat ServiceOrder (Status: Booking)
        $order = ServiceOrder::create([
            'customer_id' => $customer->id,
            'ticket_no' => $ticketNo,
            'security_pin' => $securityPin,
            'device_type' => $validated['device_type'],
            'device_brand' => $validated['device_brand'],
            'device_model' => $validated['device_model'],
            'issue_chronology' => $validated['issue_chronology'],
            'issue_photo_url' => $photoUrl,
            'addon_cleaning' => $addonCleaning,
            'addon_repasta' => $addonRepasta,
            'delivery_method' => $validated['delivery_method'],
            'pickup_address' => $pickupAddress,
            'order_status' => 'Booking',
            'unit_handed_over' => false,
        ]);

        // Buat Diagnostic awal
        Diagnostic::create([
            'service_order_id' => $order->id,
            'labor_cost' => 0,
            'parts_cost' => 0,
            'addon_cleaning_cost' => $costCleaning,
            'addon_repasta_cost' => $costRepasta,
            'net_total_cost' => $totalInitialCost,
            'is_approved_by_user' => false,
        ]);

        // Item Add-on ke order_items
        if ($addonCleaning) {
            OrderItem::create([
                'service_order_id' => $order->id,
                'item_name' => 'Add-on: Deep Cleaning & Dust Removal',
                'quantity' => 1,
                'unit_price' => 50000,
                'subtotal' => 50000,
            ]);
        }

        if ($addonRepasta) {
            OrderItem::create([
                'service_order_id' => $order->id,
                'item_name' => 'Add-on: Repasta Thermal Arctic MX-4 High-Performance',
                'quantity' => 1,
                'unit_price' => 75000,
                'subtotal' => 75000,
            ]);
        }

        // Terbitkan Invoice awal
        Invoice::create([
            'service_order_id' => $order->id,
            'invoice_no' => 'INV-' . $ticketNo,
            'amount_total' => $totalInitialCost,
            'payment_method' => 'QRIS Dinamis',
            'payment_status' => 'Unpaid',
        ]);
        // Tandai pembuat reservasi telah terverifikasi secara aman di sesi browser ini
        $request->session()->put('verified_ticket_' . $order->ticket_no, true);


        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'ticket_no' => $order->ticket_no,
                'security_pin' => $order->security_pin,
                'order' => $order,
                'ticket' => $order,
                'redirect_url' => route('user.panel', ['ticket' => $order->ticket_no]),
            ]);
        }

        return redirect()->route('user.panel', ['ticket' => $order->ticket_no])
            ->with('success', "Tiket reservasi {$order->ticket_no} berhasil diterbitkan!");
    }
}
