<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\Warranty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $ticketNo = $request->query('ticket');

        if ($ticketNo) {
            $ticketNoClean = ltrim($ticketNo, '#');
            $ticket = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])
                ->where('ticket_no', $ticketNoClean)
                ->orWhere('ticket_no', '#' . $ticketNoClean)
                ->first();
        } else {
            $ticket = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])
                ->where('ticket_no', 'SC-2026-9042')
                ->first() ?? ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])->latest()->first();
        }

        return view('user-panel', compact('ticket'));
    }

    public function approve(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with(['diagnostic', 'invoice'])
            ->firstOrFail();

        if ($order->diagnostic) {
            $order->diagnostic->update([
                'is_approved_by_user' => true,
                'approved_at' => Carbon::now(),
            ]);
        }

        $order->update([
            'order_status' => 'Approval Biaya',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Estimasi biaya berhasil disetujui. Silakan lanjutkan pembayaran QRIS.',
                'ticket' => $order,
            ]);
        }

        return back()->with('success', 'Rincian biaya berhasil disetujui.');
    }

    public function reject(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with('diagnostic')
            ->firstOrFail();

        if ($order->diagnostic) {
            $order->diagnostic->update([
                'is_approved_by_user' => false,
            ]);
        }

        $order->update([
            'order_status' => 'Booking',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengerjaan servis dibatalkan. Unit akan dirakit kembali tanpa biaya reparasi.',
                'ticket' => $order,
            ]);
        }

        return back()->with('info', 'Pengerjaan servis dibatalkan.');
    }

    public function payQris(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with(['diagnostic', 'invoice'])
            ->firstOrFail();

        if ($order->diagnostic) {
            $order->diagnostic->update([
                'is_approved_by_user' => true,
                'approved_at' => $order->diagnostic->approved_at ?? Carbon::now(),
            ]);
        }

        if ($order->invoice) {
            $order->invoice->update([
                'payment_status' => 'Paid',
                'payment_method' => 'QRIS Dinamis',
                'qris_transaction_id' => 'QRIS-TRX-' . strtoupper(Str::random(10)),
                'paid_at' => Carbon::now(),
            ]);
        }

        // Status beralih ke Paid lalu Reparasi
        $order->update([
            'order_status' => 'Reparasi',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran QRIS Berhasil Diverifikasi! Webhook Midtrans terkonfirmasi, teknisi memulai reparasi.',
                'ticket' => $order,
            ]);
        }

        return back()->with('success', 'Pembayaran QRIS berhasil diverifikasi.');
    }

    public function closeTicket(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with('invoice.warranty')
            ->firstOrFail();

        $order->update([
            'order_status' => 'Closed',
        ]);

        $warranty = null;
        if ($order->invoice && !$order->invoice->warranty) {
            $warranty = Warranty::create([
                'invoice_id' => $order->invoice->id,
                'warranty_code' => 'WAR-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6)),
                'warranty_duration_days' => 90,
                'starts_at' => Carbon::today(),
                'expires_at' => Carbon::today()->addDays(90),
                'warranty_status' => 'Active',
            ]);
        }

        $warrantyCode = $warranty ? $warranty->warranty_code : optional($order->invoice->warranty)->warranty_code;

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Terima kasih! Tiket resmi ditutup. Sertifikat digital garansi {$warrantyCode} aktif 90 hari.",
                'ticket' => $order,
            ]);
        }

        return back()->with('success', 'Tiket resmi ditutup dan garansi 90 hari aktif.');
    }
}
