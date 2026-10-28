<?php

namespace App\Http\Controllers;

use App\Models\ServiceTicket;
use App\Models\SlaLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackingController extends Controller
{
    /**
     * Tampilkan portal pelacakan tiket pelanggan.
     */
    public function index(Request $request)
    {
        $ticketNo = $request->query('ticket');

        if ($ticketNo) {
            $ticket = ServiceTicket::with(['technician', 'items'])
                ->where('ticket_no', $ticketNo)
                ->orWhere('ticket_no', '#' . ltrim($ticketNo, '#'))
                ->first();
        } else {
            // Ambil tiket aktif terbaru dari database sebagai default tampilan
            $ticket = ServiceTicket::with(['technician', 'items'])
                ->where('ticket_no', '#SC-2026-9042')
                ->first() ?? ServiceTicket::with(['technician', 'items'])->latest()->first();
        }

        return view('user-panel', compact('ticket'));
    }

    /**
     * Pelanggan menyetujui lembar rincian biaya teknisi.
     */
    public function approve(Request $request, $ticketNo)
    {
        $ticket = ServiceTicket::where('ticket_no', $ticketNo)->firstOrFail();

        $ticket->update([
            'is_approved' => true,
            'approved_at' => Carbon::now(),
            'step' => 4,
            'status' => 'Menunggu Pembayaran QRIS',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Estimasi biaya berhasil disetujui. Silakan lanjutkan pembayaran QRIS.',
                'ticket' => $ticket,
            ]);
        }

        return back()->with('success', 'Rincian biaya berhasil disetujui.');
    }

    /**
     * Pelanggan membatalkan perbaikan servis.
     */
    public function reject(Request $request, $ticketNo)
    {
        $ticket = ServiceTicket::where('ticket_no', $ticketNo)->firstOrFail();

        $ticket->update([
            'is_approved' => false,
            'status' => 'Dibatalkan Pelanggan',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengerjaan servis dibatalkan. Unit akan dirakit kembali tanpa biaya reparasi.',
                'ticket' => $ticket,
            ]);
        }

        return back()->with('info', 'Pengerjaan servis dibatalkan.');
    }

    /**
     * Simulasi pembayaran QRIS lunas secara instan.
     */
    public function payQris(Request $request, $ticketNo)
    {
        $ticket = ServiceTicket::where('ticket_no', $ticketNo)->firstOrFail();

        $ticket->update([
            'payment_status' => 'Paid',
            'payment_method' => 'QRIS',
            'payment_reference' => 'QRIS-TRX-' . strtoupper(Str::random(10)),
            'paid_at' => Carbon::now(),
            'is_approved' => true,
            'step' => 5,
            'status' => 'Sedang Dikerjakan',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran QRIS Berhasil Diverifikasi! Teknisi segera memulai proses perbaikan.',
                'ticket' => $ticket,
            ]);
        }

        return back()->with('success', 'Pembayaran QRIS berhasil diverifikasi.');
    }

    /**
     * Pelanggan menutup tiket (konfirmasi selesai) dan mengaktifkan e-Warranty 90 hari.
     */
    public function closeTicket(Request $request, $ticketNo)
    {
        $ticket = ServiceTicket::where('ticket_no', $ticketNo)->firstOrFail();

        $warrantyCode = 'WRN-' . date('Y') . '-' . mt_rand(1000, 9999);

        $ticket->update([
            'is_closed' => true,
            'closed_at' => Carbon::now(),
            'closed_by' => 'customer',
            'step' => 7,
            'status' => 'Closed / Completed',
            'warranty_code' => $warrantyCode,
            'warranty_valid_until' => Carbon::now()->addDays(90),
        ]);

        SlaLog::create([
            'service_ticket_id' => $ticket->id,
            'event_type' => 'customer_confirmed',
            'description' => 'Konsumen mengonfirmasi unit normal dan menutup tiket secara manual. Garansi e-Warranty 90 hari aktif.',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Terima kasih! Tiket resmi ditutup. Garansi digital {$warrantyCode} aktif selama 90 hari.",
                'ticket' => $ticket,
            ]);
        }

        return back()->with('success', 'Tiket resmi ditutup dan garansi aktif.');
    }
}
