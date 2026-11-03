<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ServiceOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = ServiceOrder::count();
        $inAnalysis = ServiceOrder::whereIn('order_status', ['Booking', 'Di Workshop'])->count();
        $waitingApproval = ServiceOrder::where('order_status', 'Approval Biaya')->count();
        $inProgress = ServiceOrder::where('order_status', 'Reparasi')->count();
        $completed = ServiceOrder::whereIn('order_status', ['Selesai', 'Diserahkan', 'Closed'])->count();

        $totalRevenue = Invoice::where('payment_status', 'Paid')->sum('amount_total');

        $activeTicket = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])
            ->where('ticket_no', 'SC-2026-9042')
            ->first() ?? ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])->latest()->first();

        $recentTickets = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'invoice'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'inAnalysis',
            'waitingApproval',
            'inProgress',
            'completed',
            'totalRevenue',
            'activeTicket',
            'recentTickets'
        ));
    }

    public function exportBiCsv()
    {
        $filename = 'sercomp_bi_dataset_' . date('Ymd_His') . '.csv';
        $orders = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'invoice.warranty'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ticket_no',
                'order_date',
                'customer_name',
                'device_type',
                'device_brand',
                'device_model',
                'delivery_method',
                'order_status',
                'technician_name',
                'technician_specialization',
                'labor_cost',
                'parts_cost',
                'addon_cleaning_cost',
                'addon_repasta_cost',
                'net_total_cost',
                'payment_status',
                'payment_method',
                'paid_at',
                'unit_handed_over',
                'handed_over_at',
                'sla_auto_close_at',
                'warranty_code',
                'warranty_duration_days',
                'warranty_status',
            ]);

            foreach ($orders as $o) {
                fputcsv($handle, [
                    $o->ticket_no,
                    $o->created_at?->format('Y-m-d H:i:s'),
                    $o->customer?->name ?? 'Guest',
                    $o->device_type,
                    $o->device_brand,
                    $o->device_model,
                    $o->delivery_method,
                    $o->order_status,
                    $o->technician?->name ?? 'Unassigned',
                    $o->technician?->specialization ?? '-',
                    $o->diagnostic?->labor_cost ?? 0,
                    $o->diagnostic?->parts_cost ?? 0,
                    $o->diagnostic?->addon_cleaning_cost ?? 0,
                    $o->diagnostic?->addon_repasta_cost ?? 0,
                    $o->diagnostic?->net_total_cost ?? ($o->invoice?->amount_total ?? 0),
                    $o->invoice?->payment_status ?? 'Unpaid',
                    $o->invoice?->payment_method ?? 'QRIS Dinamis',
                    $o->invoice?->paid_at?->format('Y-m-d H:i:s') ?? '',
                    $o->unit_handed_over ? '1' : '0',
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
