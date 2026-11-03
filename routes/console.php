<?php

use App\Models\ServiceOrder;
use App\Models\Warranty;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sercomp:sla-close', function () {
    $now = Carbon::now();
    $ordersToClose = ServiceOrder::query()
        ->where('unit_handed_over', true)
        ->where('order_status', '!=', 'Closed')
        ->whereNotNull('sla_auto_close_at')
        ->where('sla_auto_close_at', '<=', $now)
        ->with('invoice.warranty')
        ->get();

    $count = 0;
    foreach ($ordersToClose as $order) {
        $order->update(['order_status' => 'Closed']);

        if ($order->invoice && !$order->invoice->warranty) {
            Warranty::create([
                'invoice_id'             => $order->invoice->id,
                'warranty_code'          => 'WAR-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6)),
                'warranty_duration_days' => 90,
                'starts_at'              => Carbon::today(),
                'expires_at'             => Carbon::today()->addDays(90),
                'warranty_status'        => 'Active',
            ]);
        }
        $count++;
    }

    $this->info("Processed {$count} orders auto-closed by 24h SLA.");
})->purpose('Auto close service orders past 24h SLA deadline and issue 90-day e-Warranty');

Artisan::command('sercomp:export-bi {--path= : Lokasi path output file CSV}', function () {
    $path = $this->option('path') ?: storage_path('app/sercomp_bi_dataset.csv');
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $handle = fopen($path, 'w');
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

    $orders = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'invoice.warranty'])->get();
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

    $this->info("Dataset Business Intelligence berhasil diekstrak (" . count($orders) . " baris) ke:");
    $this->line($path);
})->purpose('Ekspor dataset transaksional servis 3NF ke format CSV untuk analisis Business Intelligence II');

Schedule::call(function () {
    $ordersToClose = ServiceOrder::query()
        ->where('unit_handed_over', true)
        ->where('order_status', '!=', 'Closed')
        ->whereNotNull('sla_auto_close_at')
        ->where('sla_auto_close_at', '<=', Carbon::now())
        ->with('invoice.warranty')
        ->get();

    foreach ($ordersToClose as $order) {
        $order->update(['order_status' => 'Closed']);

        if ($order->invoice && !$order->invoice->warranty) {
            Warranty::create([
                'invoice_id'             => $order->invoice->id,
                'warranty_code'          => 'WAR-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6)),
                'warranty_duration_days' => 90,
                'starts_at'              => Carbon::today(),
                'expires_at'             => Carbon::today()->addDays(90),
                'warranty_status'        => 'Active',
            ]);
        }
    }
})->hourly();
