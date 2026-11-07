<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerCrmController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->with(['orders.invoice', 'orders.items.sparepart', 'orders.diagnostic']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $customers = $query->get()->map(function ($c) {
            $orders = $c->orders;
            $totalOrders = $orders->count();
            $paidOrders = $orders->filter(fn($o) => in_array($o->order_status, ['Paid', 'Reparasi', 'Selesai', 'Diserahkan', 'Closed']))->count();
            $cancelledOrders = $orders->where('order_status', 'Dibatalkan')->count();
            $bookingOnly = $orders->filter(fn($o) => in_array($o->order_status, ['Booking', 'Di Workshop', 'Approval Biaya']) && (!$o->invoice || $o->invoice->payment_status !== 'Paid'))->count();

            $totalSpent = $orders->sum(fn($o) => ($o->invoice && $o->invoice->payment_status === 'Paid') ? (float)$o->invoice->amount_total : 0);

            // Klasifikasi CRM Pelanggan
            if ($paidOrders >= 2 || $totalSpent >= 750000) {
                $segment = 'Customer Setia';
                $segmentBadge = 'bg-success text-white';
            } elseif ($paidOrders === 1 || ($totalOrders > 0 && $bookingOnly > 0)) {
                $segment = 'Customer Aktif';
                $segmentBadge = 'bg-primary text-white';
            } else {
                $segment = 'Tanya-tanya Aja (Leads)';
                $segmentBadge = 'bg-warning text-dark';
            }

            $devices = $orders->map(fn($o) => "{$o->device_type} {$o->device_brand} ({$o->ticket_no})")->take(3)->values()->all();

            return (object) [
                'id' => $c->id,
                'name' => $c->name,
                'phone' => $c->phone ?? '-',
                'email' => $c->email,
                'customer_type' => $c->customer_type ?? 'Personal',
                'company_name' => $c->company_name,
                'created_at' => $c->created_at,
                'total_orders' => $totalOrders,
                'paid_orders' => $paidOrders,
                'cancelled_orders' => $cancelledOrders,
                'total_spent' => $totalSpent,
                'segment' => $segment,
                'segment_badge' => $segmentBadge,
                'devices' => $devices,
                'orders_raw' => $orders,
            ];
        });

        if ($request->filled('type')) {
            $customers = $customers->filter(fn($c) => $c->customer_type === $request->type);
        }

        if ($request->filled('segment')) {
            $customers = $customers->filter(fn($c) => $c->segment === $request->segment);
        }

        $countTotal = $customers->count();
        $countPersonal = $customers->where('customer_type', 'Personal')->count();
        $countCompany = $customers->where('customer_type', 'Company')->count();
        $countSetia = $customers->where('segment', 'Customer Setia')->count();
        $countAktif = $customers->where('segment', 'Customer Aktif')->count();
        $countTanya = $customers->where('segment', 'Tanya-tanya Aja (Leads)')->count();

        return view('admin.crm', compact(
            'customers',
            'countTotal',
            'countPersonal',
            'countCompany',
            'countSetia',
            'countAktif',
            'countTanya'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'customer_type' => 'required|in:Personal,Company',
            'company_name' => 'nullable|string|max:150',
            'phone' => 'required|string|max:25|unique:users,phone',
            'email' => 'nullable|email|max:100|unique:users,email',
            'notes' => 'nullable|string',
        ]);

        $email = $validated['email'] ?? (preg_replace('/[^0-9]/', '', $validated['phone']) . '@customer.sercomp.id');

        User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'customer_type' => $validated['customer_type'],
            'company_name' => ($validated['customer_type'] === 'Company') ? ($validated['company_name'] ?? null) : null,
            'specialization' => $validated['notes'] ?? 'Akun Terdaftar via Admin CRM',
            'is_active' => true,
        ]);

        return back()->with('success', 'Akun pelanggan khusus berhasil ditambahkan ke database CRM SerComp.');
    }
}
