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
        $orders = ServiceOrder::with([
            'customer.orders.invoice',
            'technician',
            'diagnostic',
            'items.sparepart.categoryRelation',
            'invoice.warranty',
            'invoice.payments',
            'statusHistories',
        ])->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel / Power BI compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ticket_no',
                'order_timestamp',
                'branch_code',
                'lead_source',
                'customer_id',
                'customer_type',
                'company_name',
                'customer_name',
                'crm_segment_historical',
                'device_type',
                'device_brand',
                'device_model',
                'serial_number',
                'symptom_category',
                'fault_category',
                'delivery_method',
                'order_status',
                'negotiation_status',
                'negotiation_budget',
                'technician_name',
                'technician_specialization',
                'sparepart_items_details',
                'total_items_qty',
                'parts_cost_price_total',
                'parts_selling_price_total',
                'parts_margin_profit_total',
                'labor_cost',
                'addon_cleaning_cost',
                'addon_repasta_cost',
                'net_total_cost',
                'payment_status',
                'payment_method_primary',
                'payments_count',
                'amount_paid_total',
                'paid_at_first',
                'unit_handed_over',
                'handover_method',
                'courier_name',
                'receiver_name',
                'has_digital_signature',
                'handed_over_at',
                'sla_auto_close_at',
                'rating_score',
                'warranty_code',
                'warranty_duration_days',
                'warranty_status',
                'tat_booking_to_workshop_hours',
                'tat_total_turnaround_hours',
            ]);

            foreach ($orders as $o) {
                // CRM segment snapshot / fallback
                $crmSegment = $o->crm_segment_snapshot ?? 'Leads';

                // Parts item details with QTY, Category & Margin
                $partsDetails = $o->items->map(function ($it) {
                    $catName = $it->sparepart?->categoryRelation?->name ?? ($it->sparepart?->category ?? 'General');
                    return "{$it->item_name} [Qty: {$it->quantity}, Cat: {$catName}, Modal: {$it->cost_price}, Jual: {$it->unit_price}]";
                })->implode('; ');

                $totalQty = $o->items->sum('quantity');
                $totalCostPrice = $o->items->sum(fn($it) => $it->cost_price * $it->quantity);
                $totalSellingPrice = $o->items->sum(fn($it) => $it->unit_price * $it->quantity);
                $totalMargin = $o->items->sum('margin_total') ?: ($totalSellingPrice - $totalCostPrice);

                // Payments breakdown
                $payments = $o->invoice?->payments ?? collect();
                $paymentsCount = $payments->count();
                $amountPaidTotal = $payments->where('status', 'Success')->sum('amount');
                $firstPaidAt = $payments->first()?->paid_at ?? $o->invoice?->paid_at;

                // Turnaround Time (TAT) calculations from Status Histories
                $histBooking = $o->statusHistories->where('status_to', 'Booking')->first();
                $histWorkshop = $o->statusHistories->where('status_to', 'Di Workshop')->first();
                $histSelesai = $o->statusHistories->where('status_to', 'Selesai')->first();

                $tatBookingWorkshopHours = '';
                if ($histBooking && $histWorkshop) {
                    $tatBookingWorkshopHours = round(abs(Carbon::parse($histWorkshop->changed_at)->diffInMinutes(Carbon::parse($histBooking->changed_at))) / 60, 2);
                }

                $tatTotalHours = '';
                if ($o->created_at && $o->handed_over_at) {
                    $tatTotalHours = round(abs(Carbon::parse($o->handed_over_at)->diffInMinutes(Carbon::parse($o->created_at))) / 60, 2);
                }

                fputcsv($handle, [
                    $o->ticket_no,
                    $o->created_at?->format('Y-m-d H:i:s'),
                    $o->branch_code ?? 'JKT-PST-01',
                    $o->lead_source ?? 'Website Direct',
                    $o->customer_id ?? '',
                    $o->customer_type ?? ($o->customer?->customer_type ?? 'Personal'),
                    $o->company_name ?? ($o->customer?->company_name ?? ''),
                    $o->customer?->name ?? 'Guest',
                    $crmSegment,
                    $o->device_type,
                    $o->device_brand,
                    $o->device_model,
                    $o->serial_number ?? '',
                    $o->diagnostic?->symptom_category ?? '',
                    $o->diagnostic?->fault_category ?? '',
                    $o->delivery_method,
                    $o->order_status,
                    $o->negotiation_status ?? 'None',
                    $o->negotiation_budget ?? 0,
                    $o->technician?->name ?? 'Unassigned',
                    $o->technician?->specialization ?? '',
                    $partsDetails ?: 'Tanpa Penggantian Suku Cadang',
                    $totalQty,
                    $totalCostPrice,
                    $totalSellingPrice,
                    $totalMargin,
                    $o->diagnostic?->labor_cost ?? 0,
                    $o->diagnostic?->addon_cleaning_cost ?? 0,
                    $o->diagnostic?->addon_repasta_cost ?? 0,
                    $o->diagnostic?->net_total_cost ?? ($o->invoice?->amount_total ?? 0),
                    $o->invoice?->payment_status ?? 'Unpaid',
                    $o->invoice?->payment_method ?? 'QRIS Dinamis',
                    $paymentsCount,
                    $amountPaidTotal ?: ($o->invoice?->payment_status === 'Paid' ? (float)($o->invoice->amount_total ?? 0) : 0),
                    $firstPaidAt ? Carbon::parse($firstPaidAt)->format('Y-m-d H:i:s') : '',
                    $o->unit_handed_over ? '1' : '0',
                    $o->handover_method ?? ($o->delivery_method === 'Ambil oleh Teknisi' ? 'Courier Delivery' : 'Pickup Counter'),
                    $o->courier_name ?? '',
                    $o->receiver_name ?? '',
                    !empty($o->signature_data) ? '1' : '0',
                    $o->handed_over_at?->format('Y-m-d H:i:s') ?? '',
                    $o->sla_auto_close_at?->format('Y-m-d H:i:s') ?? '',
                    $o->rating_score ?? '',
                    $o->invoice?->warranty?->warranty_code ?? '',
                    $o->invoice?->warranty?->warranty_duration_days ?? '',
                    $o->invoice?->warranty?->warranty_status ?? '',
                    $tatBookingWorkshopHours,
                    $tatTotalHours,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
