<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Jika login sebagai teknisi, arahkan ke dashboard operasional teknisi
        if (auth()->check() && auth()->user()->role === 'technician') {
            return redirect()->route('teknisi.dashboard');
        }

        $totalOrders = ServiceOrder::count();
        $inAnalysis = ServiceOrder::whereIn('order_status', ['Booking', 'Di Workshop'])->count();
        $waitingApproval = ServiceOrder::where('order_status', 'Approval Biaya')->count();
        $waitingNego = ServiceOrder::where('negotiation_status', 'Requested')->count();
        $inProgress = ServiceOrder::where('order_status', 'Reparasi')->count();
        $completed = ServiceOrder::whereIn('order_status', ['Selesai', 'Diserahkan', 'Closed'])->count();

        $totalRevenue = Invoice::where('payment_status', 'Paid')->sum('amount_total');

        $activeTicket = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])
            ->where('ticket_no', 'SC-2026-9042')
            ->first() ?? ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])->latest()->first();

        $recentTickets = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'invoice'])->latest()->take(6)->get();

        // CRM Pelanggan Overview
        $totalCustomers = User::where('role', 'customer')->count();
        $customers = User::where('role', 'customer')->with('orders.invoice')->get();
        $countSetia = 0;
        $countTanyaTanya = 0;
        $countAktif = 0;

        foreach ($customers as $c) {
            $paidCount = $c->orders->filter(fn($o) => in_array($o->order_status, ['Paid', 'Reparasi', 'Selesai', 'Diserahkan', 'Closed']))->count();
            $spent = $c->orders->sum(fn($o) => ($o->invoice && $o->invoice->payment_status === 'Paid') ? (float)$o->invoice->amount_total : 0);

            if ($paidCount >= 2 || $spent >= 750000) {
                $countSetia++;
            } elseif ($paidCount === 1) {
                $countAktif++;
            } else {
                $countTanyaTanya++;
            }
        }

        return view('admin.dashboard', compact(
            'totalOrders',
            'inAnalysis',
            'waitingApproval',
            'waitingNego',
            'inProgress',
            'completed',
            'totalRevenue',
            'activeTicket',
            'recentTickets',
            'totalCustomers',
            'countSetia',
            'countAktif',
            'countTanyaTanya'
        ));
    }

    public function technicianDashboard()
    {
        $techId = auth()->id();

        // Antrean khusus operasional teknisi: HANYA yang di-assign ke teknisi ini
        $myActiveOrders = ServiceOrder::with(['customer', 'diagnostic', 'items.sparepart', 'invoice'])
            ->where('technician_id', $techId)
            ->whereIn('order_status', ['Di Workshop', 'Reparasi', 'Selesai'])
            ->latest()
            ->get();

        $queueCheck = ServiceOrder::where('technician_id', $techId)->whereIn('order_status', ['Booking', 'Di Workshop'])->count();
        $queueRepair = ServiceOrder::where('technician_id', $techId)->where('order_status', 'Reparasi')->count();
        $queueDualEvidence = ServiceOrder::where('technician_id', $techId)
            ->where('order_status', 'Selesai')
            ->whereHas('diagnostic', function ($q) {
                $q->whereNull('evidence_running_test_photo')->orWhereNull('evidence_replaced_part_photo');
            })->count();
        $myCompletedCount = ServiceOrder::where('technician_id', $techId)->whereIn('order_status', ['Selesai', 'Diserahkan', 'Closed'])->count();

        $recentQueue = ServiceOrder::with(['customer', 'diagnostic'])
            ->where('technician_id', $techId)
            ->latest()
            ->take(6)
            ->get();

        return view('admin.technician-dashboard', compact(
            'myActiveOrders',
            'queueCheck',
            'queueRepair',
            'queueDualEvidence',
            'myCompletedCount',
            'recentQueue'
        ));
    }

    public function exportBiCsv()
    {
        $filename = 'sercomp_bi_dataset_' . date('Ymd_His') . '.csv';
        $orders = ServiceOrder::with(['customer.orders.invoice', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ticket_no',
                'order_date',
                'customer_id',
                'customer_type',
                'company_name',
                'customer_name',
                'customer_crm_segment',
                'device_brand',
                'device_model',
                'delivery_method',
                'order_status',
                'negotiation_status',
                'negotiation_budget',
                'technician_name',
                'technician_specialization',
                'sparepart_items_and_category',
                'labor_cost',
                'parts_cost',
                'addon_cleaning_cost',
                'addon_repasta_cost',
                'net_total_cost',
                'payment_status',
                'payment_method',
                'paid_at',
                'unit_handed_over',
                'courier_name',
                'receiver_name',
                'has_digital_signature',
                'handed_over_at',
                'sla_auto_close_at',
                'warranty_code',
                'warranty_duration_days',
                'warranty_status',
            ]);

            foreach ($orders as $o) {
                // CRM segment calculation for BI
                $crmSegment = 'Tanya-tanya Aja (Leads)';
                if ($o->customer) {
                    $paidCount = $o->customer->orders->filter(fn($co) => in_array($co->order_status, ['Paid', 'Reparasi', 'Selesai', 'Diserahkan', 'Closed']))->count();
                    $spent = $o->customer->orders->sum(fn($co) => ($co->invoice && $co->invoice->payment_status === 'Paid') ? (float)$co->invoice->amount_total : 0);
                    if ($paidCount >= 2 || $spent >= 750000) {
                        $crmSegment = 'Customer Setia';
                    } elseif ($paidCount === 1) {
                        $crmSegment = 'Customer Aktif';
                    }
                }

                $sparepartsSummary = $o->items->map(fn($item) => "{$item->item_name} (" . ($item->sparepart?->category ?? 'General') . ")")->implode('; ');

                fputcsv($handle, [
                    $o->ticket_no,
                    $o->created_at?->format('Y-m-d H:i:s'),
                    $o->customer_id ?? '',
                    $o->customer_type ?? ($o->customer?->customer_type ?? 'Personal'),
                    $o->company_name ?? ($o->customer?->company_name ?? '-'),
                    $o->customer?->name ?? 'Guest',
                    $crmSegment,
                    $o->device_brand,
                    $o->device_model,
                    $o->delivery_method,
                    $o->order_status,
                    $o->negotiation_status ?? 'None',
                    $o->negotiation_budget ?? 0,
                    $o->technician?->name ?? 'Unassigned',
                    $o->technician?->specialization ?? '-',
                    $sparepartsSummary ?: 'Tidak Ada Sparepart',
                    $o->diagnostic?->labor_cost ?? 0,
                    $o->diagnostic?->parts_cost ?? 0,
                    $o->diagnostic?->addon_cleaning_cost ?? 0,
                    $o->diagnostic?->addon_repasta_cost ?? 0,
                    $o->diagnostic?->net_total_cost ?? ($o->invoice?->amount_total ?? 0),
                    $o->invoice?->payment_status ?? 'Unpaid',
                    $o->invoice?->payment_method ?? 'QRIS Dinamis',
                    $o->invoice?->paid_at?->format('Y-m-d H:i:s') ?? '',
                    $o->unit_handed_over ? '1' : '0',
                    $o->courier_name ?? '',
                    $o->receiver_name ?? '',
                    !empty($o->signature_data) ? '1' : '0',
                    $o->handed_over_at?->format('Y-m-d H:i:s') ?? '',
                    $o->sla_auto_close_at?->format('Y-m-d H:i:s') ?? '',
                    $o->invoice?->warranty?->warranty_code ?? '',
                    $o->invoice?->warranty?->warranty_duration_days ?? '',
                    $o->invoice?->warranty?->warranty_status ?? '',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
