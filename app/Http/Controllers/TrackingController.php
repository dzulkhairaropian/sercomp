<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\Warranty;
use App\Services\NotificationService;
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

        $isVerified = $ticket ? ($request->session()->get('verified_ticket_' . $ticket->ticket_no) === true) : false;
        return view('user-panel', compact('ticket', 'isVerified'));
    }

    protected function verifyCustomerOwnership(Request $request, ServiceOrder $order): bool
    {
        $sessionKey = 'verified_ticket_' . $order->ticket_no;
        if ($request->session()->get($sessionKey) === true) {
            return true;
        }

        $inputPin = trim((string) $request->input('security_pin', $request->input('phone_verification', '')));
        $actualPin = (string) ($order->security_pin ?? '');

        if (empty($inputPin)) {
            return false;
        }

        // 1. Verifikasi utama: Cocokkan 4 kode PIN rahasia tiket
        if (!empty($actualPin) && $actualPin === $inputPin) {
            $request->session()->put($sessionKey, true);
            return true;
        }

        // 2. Fallback darurat: 4 digit nomor telepon pelanggan
        $actualPhone = preg_replace('/[^0-9]/', '', $order->customer?->phone ?? '');
        if (!empty($actualPhone) && strlen($inputPin) >= 4 && str_ends_with($actualPhone, $inputPin)) {
            $request->session()->put($sessionKey, true);
            return true;
        }

        return false;
    }

    public function approve(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with(['diagnostic', 'invoice'])
            ->firstOrFail();

        if (!$this->verifyCustomerOwnership($request, $order)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'require_verification' => true,
                    'message' => 'Otorisasi ditolak. Masukkan 4 digit Kode PIN Keamanan tiket ini.',
                ], 403);
            }
            return back()->withErrors(['security_pin' => 'Kode PIN Keamanan 4 digit tidak cocok.']);
        }
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
            ->with(['diagnostic', 'invoice'])
            ->firstOrFail();

        if (!$this->verifyCustomerOwnership($request, $order)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'require_verification' => true,
                    'message' => 'Otorisasi ditolak. Masukkan 4 digit Kode PIN Keamanan tiket ini.',
                ], 403);
            }
            return back()->withErrors(['security_pin' => 'Kode PIN Keamanan 4 digit tidak cocok.']);
        }
        if ($order->diagnostic) {
            $order->diagnostic->update([
                'is_approved_by_user' => false,
            ]);
        }

        $order->update([
            'order_status' => 'Dibatalkan',
        ]);

        if ($order->invoice) {
            $order->invoice->update([
                'payment_status' => 'Cancelled',
                'amount_total' => 0,
            ]);
        }
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengerjaan servis dibatalkan. Unit akan dirakit kembali tanpa biaya reparasi.',
                'ticket' => $order,
            ]);
        }

        return back()->with('info', 'Pengerjaan servis dibatalkan.');
    }

    public function negotiate(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with(['diagnostic', 'invoice', 'customer'])
            ->firstOrFail();

        if (!$this->verifyCustomerOwnership($request, $order)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'require_verification' => true,
                    'message' => 'Otorisasi ditolak. Masukkan 4 digit Kode PIN Keamanan tiket ini.',
                ], 403);
            }
            return back()->withErrors(['security_pin' => 'Kode PIN Keamanan 4 digit tidak cocok.']);
        }

        $validated = $request->validate([
            'negotiation_notes' => 'required|string|max:500',
            'negotiation_budget' => 'nullable|numeric|min:0',
        ]);

        $order->update([
            'negotiation_status' => 'Requested',
            'negotiation_notes' => $validated['negotiation_notes'],
            'negotiation_budget' => $validated['negotiation_budget'] ?? null,
        ]);

        $notifData = NotificationService::notifyNegotiationRequested($order);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan negosiasi Anda berhasil dikirim ke Admin Workshop. Notifikasi otomatis terkirim via WA & Email.',
                'wa_link' => $notifData['wa_link'] ?? null,
                'ticket' => $order->fresh(['diagnostic', 'invoice']),
            ]);
        }

        return back()->with('success', 'Pengajuan negosiasi biaya telah dikirim ke Admin Workshop.');
    }

    public function payQris(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with(['diagnostic', 'invoice.payments'])
            ->firstOrFail();

        if ($order->diagnostic) {
            $order->diagnostic->update([
                'is_approved_by_user' => true,
                'approved_at' => $order->diagnostic->approved_at ?? Carbon::now(),
            ]);
        }

        $paymentMode = $request->input('payment_mode', 'full'); // 'dp' (50%) atau 'full' (100%)
        $totalCost = (float) ($order->invoice?->amount_total ?? $order->diagnostic?->net_total_cost ?? 0);
        $currentOutstanding = (float) ($order->invoice?->outstanding ?? $totalCost);

        if ($paymentMode === 'dp') {
            // Down Payment 50% atau sisa jika kurang
            $payAmount = round($totalCost * 0.5, 2);
            if ($payAmount > $currentOutstanding) {
                $payAmount = $currentOutstanding;
            }
            $pType = \App\Models\Payment::TYPE_DP;
            $note = 'Pembayaran Uang Muka / Down Payment (DP) 50% via QRIS Dinamis.';
        } else {
            // Pelunasan / Full
            $payAmount = $currentOutstanding > 0 ? $currentOutstanding : $totalCost;
            $pType = $order->invoice?->total_paid > 0 ? \App\Models\Payment::TYPE_PELUNASAN : \App\Models\Payment::TYPE_FULL;
            $note = 'Pembayaran Lunas via QRIS Dinamis.';
        }

        $trxRef = 'QRIS-' . strtoupper(Str::random(10));

        if ($order->invoice) {
            // Catat transaksi keuangan resmi di tabel payments
            $order->invoice->payments()->create([
                'payment_reference' => $trxRef,
                'payment_type' => $pType,
                'amount' => $payAmount,
                'payment_method' => 'QRIS Dinamis',
                'status' => 'Success',
                'paid_at' => Carbon::now(),
                'notes' => $note,
            ]);

            // Refresh model invoice untuk mendapatkan kalkulasi derived status
            $order->invoice->refresh();
            $derivedStatus = $order->invoice->derived_payment_status;

            $order->invoice->update([
                'payment_status' => $derivedStatus,
                'payment_method' => 'QRIS Dinamis',
                'qris_transaction_id' => $trxRef,
                'paid_at' => $derivedStatus === 'Paid' ? Carbon::now() : ($order->invoice->paid_at ?? null),
            ]);
        }

        // Status beralih ke Reparasi jika unit belum diperbaiki
        if (in_array($order->order_status, ['Approval Biaya', 'Booking', 'Di Workshop'])) {
            $order->recordStatusChange('Reparasi', null, "Pembayaran {$pType} Rp " . number_format($payAmount, 0, ',', '.') . " terkonfirmasi.");
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'payment_type' => $pType,
                'amount_paid' => $payAmount,
                'outstanding' => $order->invoice?->outstanding ?? 0,
                'payment_status' => $order->invoice?->derived_payment_status,
                'message' => "Pembayaran {$pType} sebesar Rp " . number_format($payAmount, 0, ',', '.') . " Berhasil Diverifikasi! Teknisi melanjutkan perbaikan hardware.",
                'ticket' => $order->fresh(['diagnostic', 'invoice.payments']),
            ]);
        }

        return back()->with('success', "Pembayaran {$pType} berhasil diverifikasi.");
    }

    public function closeTicket(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with('invoice.warranty')
            ->firstOrFail();

        if (!$this->verifyCustomerOwnership($request, $order)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'require_verification' => true,
                    'message' => 'Otorisasi ditolak. Masukkan 4 digit Kode PIN Keamanan tiket ini.',
                ], 403);
            }
            return back()->withErrors(['security_pin' => 'Kode PIN Keamanan 4 digit tidak cocok.']);
        }
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
