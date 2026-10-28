<?php

namespace App\Http\Controllers;

use App\Models\ServiceTicket;
use App\Models\ServiceTicketItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    /**
     * Tampilkan formulir reservasi tiket servis.
     */
    public function create()
    {
        return view('booking');
    }

    /**
     * Simpan data tiket reservasi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_type' => 'required|in:Laptop,PC Desktop',
            'device_brand' => 'required|string|max:100',
            'device_model' => 'required|string|max:150',
            'issue_chronology' => 'required|string',
            'delivery_method' => 'required|in:Diantar Sendiri,Ambil oleh Teknisi',
            'pickup_address' => 'required_if:delivery_method,Ambil oleh Teknisi|nullable|string',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:100',
            'photo' => 'nullable|image|max:5120',
            'addon_cleaning' => 'nullable|boolean',
            'addon_repasta' => 'nullable|boolean',
        ]);

        // Generate nomor tiket acak unik
        do {
            $ticketNo = '#SC-' . date('Y') . '-' . mt_rand(1000, 9999);
        } while (ServiceTicket::where('ticket_no', $ticketNo)->exists());

        // Handle upload foto kerusakan fisik (jika ada)
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('issues', 'public');
        }

        $addonCleaning = $request->boolean('addon_cleaning');
        $addonRepasta = $request->boolean('addon_repasta');

        $costCleaning = $addonCleaning ? 50000 : 0;
        $costRepasta = $addonRepasta ? 75000 : 0;
        $totalInitialCost = $costCleaning + $costRepasta;

        // Generate nomor invoice awal
        $invoiceNo = 'INV-SC-' . date('Y') . '-' . mt_rand(1000, 9999);

        $initialStatus = $validated['delivery_method'] === 'Diantar Sendiri' 
            ? 'Menunggu Unit Tiba di Workshop' 
            : 'Menunggu Penjemputan Unit oleh Teknisi';

        // Buat record tiket servis di MySQL
        $ticket = ServiceTicket::create([
            'ticket_no' => $ticketNo,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'device_type' => $validated['device_type'],
            'device_brand' => $validated['device_brand'],
            'device_model' => $validated['device_model'],
            'issue_chronology' => $validated['issue_chronology'],
            'issue_photo_path' => $photoPath,
            'delivery_method' => $validated['delivery_method'],
            'pickup_address' => $validated['pickup_address'] ?? null,
            'cost_cleaning' => $costCleaning,
            'cost_repasta' => $costRepasta,
            'cost_labor' => 0,
            'cost_parts' => 0,
            'total_cost' => $totalInitialCost,
            'step' => 1,
            'status' => $initialStatus,
            'payment_status' => 'Unpaid',
            'payment_method' => 'QRIS',
            'invoice_no' => $invoiceNo,
        ]);

        // Simpan item add-on jika dipilih
        if ($addonCleaning) {
            ServiceTicketItem::create([
                'service_ticket_id' => $ticket->id,
                'item_type' => 'addon',
                'item_name' => 'Add-on: Deep Cleaning & Dust Removal',
                'quantity' => 1,
                'unit_price' => 50000,
                'subtotal' => 50000,
            ]);
        }

        if ($addonRepasta) {
            ServiceTicketItem::create([
                'service_ticket_id' => $ticket->id,
                'item_type' => 'addon',
                'item_name' => 'Add-on: Repasta Thermal Arctic MX-4 High-Performance',
                'quantity' => 1,
                'unit_price' => 75000,
                'subtotal' => 75000,
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'ticket_no' => $ticket->ticket_no,
                'ticket' => $ticket,
                'redirect_url' => route('user.panel', ['ticket' => $ticket->ticket_no]),
            ]);
        }

        return redirect()->route('user.panel', ['ticket' => $ticket->ticket_no])
            ->with('success', "Tiket reservasi {$ticket->ticket_no} berhasil diterbitkan!");
    }
}
