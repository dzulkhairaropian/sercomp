<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnostic;
use App\Models\OrderItem;
use App\Models\ServiceOrder;
use App\Models\Sparepart;
use App\Models\User;
use App\Models\Warranty;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceOrderController extends Controller
{
    public function cashierIndex(Request $request)
    {
        $orders = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'invoice.warranty'])
            ->whereIn('order_status', ['Selesai', 'Diserahkan', 'Closed'])
            ->latest()
            ->get();

        $countUnpaidReady = ServiceOrder::where('order_status', 'Selesai')
            ->whereHas('invoice', fn($q) => $q->where('payment_status', '!=', 'Paid'))
            ->count();

        $countReadyHandover = ServiceOrder::where('order_status', 'Selesai')
            ->where('unit_handed_over', false)
            ->count();

        return view('admin.cashier', compact('orders', 'countUnpaidReady', 'countReadyHandover'));
    }

    public function payCashOrPos(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with(['invoice', 'customer'])
            ->firstOrFail();

        $validated = $request->validate([
            'payment_method' => 'required|in:Tunai / Kasir,Transfer Bank / EDC,QRIS Dinamis',
        ]);

        if ($order->invoice) {
            $order->invoice->update([
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'Paid',
                'paid_at' => Carbon::now(),
            ]);
        }

        NotificationService::notifyPaymentReceived($order);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat oleh Kasir Workshop!',
                'ticket' => $order->fresh('invoice'),
            ]);
        }

        return back()->with('success', 'Pembayaran sebesar Rp ' . number_format($order->invoice?->amount_total ?? 0, 0, ',', '.') . ' berhasil diverifikasi!');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'invoice'])->latest();

        // Jika teknisi, HANYA tampilkan tiket yang ditugaskan ke dirinya
        if ($user && $user->role === 'technician') {
            $query->where('technician_id', $user->id);
        }

        if ($request->filled('device')) {
            $query->where('device_type', $request->device);
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_no', 'like', "%{$search}%")
                  ->orWhere('device_brand', 'like', "%{$search}%")
                  ->orWhere('device_model', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->get();

        $baseCountQuery = ServiceOrder::query();
        if ($user && $user->role === 'technician') {
            $baseCountQuery->where('technician_id', $user->id);
        }

        $countAll = (clone $baseCountQuery)->count();
        $countLaptop = (clone $baseCountQuery)->where('device_type', 'Laptop')->count();
        $countPc = (clone $baseCountQuery)->where('device_type', 'PC Desktop')->count();
        $countApproval = (clone $baseCountQuery)->where('order_status', 'Approval Biaya')->count();
        $countProgress = (clone $baseCountQuery)->where('order_status', 'Reparasi')->count();
        $countCompleted = (clone $baseCountQuery)->whereIn('order_status', ['Selesai', 'Diserahkan', 'Closed'])->count();
        $countCancelled = (clone $baseCountQuery)->where('order_status', 'Dibatalkan')->count();

        // Ambil daftar teknisi untuk modal assign oleh admin
        $technicians = $user?->role === 'admin' ? User::where('role', 'technician')->where('is_active', true)->get() : collect();

        return view('admin.service-list', compact(
            'tickets',
            'countAll',
            'countLaptop',
            'countPc',
            'countApproval',
            'countProgress',
            'countCompleted',
            'countCancelled',
            'technicians'
        ));
    }

    public function showWorkbench(Request $request, $ticketNo = null)
    {
        $ticketNo = $ticketNo ?? $request->query('ticket');

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

        // Pilihan sparepart: jika teknisi, hanya nama & kode (tanpa harga bila perlu), admin lengkap
        $spareparts = Sparepart::orderBy('part_name')->get();
        $technicians = auth()->user()?->role === 'admin' ? User::where('role', 'technician')->where('is_active', true)->get() : collect();

        return view('admin.workbench', compact('ticket', 'spareparts', 'technicians'));
    }

    public function assignTechnician(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->firstOrFail();

        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id',
        ]);

        $order->update([
            'technician_id' => $validated['technician_id'],
            'order_status' => in_array($order->order_status, ['Booking']) ? 'Di Workshop' : $order->order_status,
        ]);

        $tech = User::find($validated['technician_id']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Teknisi ' . ($tech?->name ?? '') . ' berhasil ditugaskan untuk tiket ' . $order->ticket_no,
                'ticket' => $order->fresh('technician'),
            ]);
        }

        return back()->with('success', 'Teknisi ' . ($tech?->name ?? '') . ' berhasil ditugaskan untuk tiket ' . $order->ticket_no);
    }

    public function updateDiagnosis(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with(['diagnostic', 'invoice'])
            ->firstOrFail();

        $isTechnician = auth()->user()?->role === 'technician';

        if ($isTechnician && (!$request->filled('cost_labor') && !$request->filled('cost_parts'))) {
            $validated = $request->validate([
                'diagnosis' => 'required|string',
                'action_plan' => 'required|string',
                'sparepart_id' => 'nullable|exists:spareparts,id',
            ]);
            // Teknisi input temuan fisik tanpa menentukan harga
            $diagnostic = $order->diagnostic ?? new Diagnostic(['service_order_id' => $order->id]);
            $labor = (float)($diagnostic->labor_cost ?? 0);
            $parts = (float)($diagnostic->parts_cost ?? 0);
            $priceSetByAdmin = false;
        } else {
            $validated = $request->validate([
                'diagnosis' => 'required|string',
                'action_plan' => 'required|string',
                'cost_labor' => 'required|numeric|min:0',
                'cost_parts' => 'required|numeric|min:0',
                'sparepart_id' => 'nullable|exists:spareparts,id',
            ]);
            $labor = (float) $validated['cost_labor'];
            $parts = (float) $validated['cost_parts'];
            $priceSetByAdmin = true;
        }

        $diagnostic = $order->diagnostic ?? new Diagnostic(['service_order_id' => $order->id]);
        $addonCleaning = $diagnostic->addon_cleaning_cost ?? ($order->addon_cleaning ? 50000 : 0);
        $addonRepasta = $diagnostic->addon_repasta_cost ?? ($order->addon_repasta ? 75000 : 0);
        $netTotal = $labor + $parts + $addonCleaning + $addonRepasta;

        $diagnostic->fill([
            'physical_damage_analysis' => $validated['diagnosis'],
            'action_plan' => $validated['action_plan'],
            'labor_cost' => $labor,
            'parts_cost' => $parts,
            'addon_cleaning_cost' => $addonCleaning,
            'addon_repasta_cost' => $addonRepasta,
            'net_total_cost' => $netTotal,
            'estimated_by' => auth()->id(),
            'admin_notes' => $request->input('admin_notes', $diagnostic->admin_notes),
            'is_price_locked' => true,
        ])->save();

        // Tambah item sparepart jika dipilih
        if (!empty($validated['sparepart_id'])) {
            $part = Sparepart::find($validated['sparepart_id']);
            if ($part) {
                OrderItem::create([
                    'service_order_id' => $order->id,
                    'sparepart_id' => $part->id,
                    'item_name' => $part->part_name,
                    'quantity' => 1,
                    'unit_price' => $part->unit_price,
                    'subtotal' => $part->unit_price,
                ]);
            }
        }

        // Sinkronisasi invoice total
        if ($order->invoice) {
            $order->invoice->update([
                'amount_total' => $netTotal,
            ]);
        }

        // Status beralih ke Approval Biaya (Tahap 3) jika harga ditetapkan, atau tetap Di Workshop jika diagnosa awal
        $newStatus = $priceSetByAdmin ? 'Approval Biaya' : ($order->order_status === 'Booking' ? 'Di Workshop' : $order->order_status);
        $order->update([
            'order_status' => $newStatus,
        ]);

        // Otomatis kirim notifikasi WA & Email ke pelanggan hanya jika harga resmi sudah ditetapkan
        if ($priceSetByAdmin) {
            NotificationService::notifyEstimateReady($order);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lembar persetujuan berhasil diperbarui dan disinkronkan ke portal pelanggan!',
                'ticket' => $order->fresh(['diagnostic', 'invoice', 'items']),
            ]);
        }

        return back()->with('success', 'Lembar diagnosa dan persetujuan biaya berhasil disimpan.');
    }

    public function complete(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with('diagnostic')
            ->firstOrFail();

        // Dual evidence photo validation (running test normal & part lama)
        $request->validate([
            'evidence_running_test' => 'nullable|image|max:5120',
            'evidence_replaced_part' => 'nullable|image|max:5120',
        ]);

        $diagnostic = $order->diagnostic ?? new Diagnostic(['service_order_id' => $order->id]);

        if ($request->hasFile('evidence_running_test')) {
            $diagnostic->evidence_running_test_photo = $request->file('evidence_running_test')->store('evidence', 'public');
        }
        if ($request->hasFile('evidence_replaced_part')) {
            $diagnostic->evidence_replaced_part_photo = $request->file('evidence_replaced_part')->store('evidence', 'public');
        }
        $diagnostic->save();

        $order->update([
            'order_status' => 'Selesai',
        ]);

        // Otomatis notif WA & Email: unit selesai & lolos running test
        NotificationService::notifyOrderCompleted($order);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Servis unit ditandai selesai! Bukti foto running test & part lama terlampir.',
                'ticket' => $order->fresh(['diagnostic', 'invoice']),
            ]);
        }

        return back()->with('success', 'Servis unit selesai dikerjakan.');
    }

    public function handover(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->firstOrFail();

        $now = Carbon::now();
        $order->update([
            'order_status' => 'Diserahkan',
            'unit_handed_over' => true,
            'handed_over_at' => $now,
            'sla_auto_close_at' => $now->copy()->addHours(24),
            'courier_name' => $request->input('courier_name', $order->courier_name ?? 'Kurir Workshop SerComp'),
            'receiver_name' => $request->input('receiver_name', $order->customer?->name ?? 'Pelanggan'),
            'signature_data' => $request->input('signature_data', $order->signature_data),
        ]);

        // Otomatis notif WA & Email serah terima dengan kurir & tanda tangan
        NotificationService::notifyOrderHandedOver($order);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit diserahkan ke pelanggan. Timer toleransi uji 1x24 jam aktif!',
                'ticket' => $order,
            ]);
        }

        return back()->with('success', 'Unit diserahkan. SLA timer 24 jam aktif.');
    }

    public function resolveNegotiation(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with(['diagnostic', 'invoice'])
            ->firstOrFail();

        $validated = $request->validate([
            'decision' => 'required|in:approve,reject',
            'admin_notes' => 'required|string',
            'adjusted_labor' => 'nullable|numeric|min:0',
            'adjusted_parts' => 'nullable|numeric|min:0',
        ]);

        $diagnostic = $order->diagnostic ?? new Diagnostic(['service_order_id' => $order->id]);
        $isApproved = $validated['decision'] === 'approve';

        if ($isApproved) {
            $labor = $validated['adjusted_labor'] !== null ? (float)$validated['adjusted_labor'] : (float)$diagnostic->labor_cost;
            $parts = $validated['adjusted_parts'] !== null ? (float)$validated['adjusted_parts'] : (float)$diagnostic->parts_cost;
            $addonCleaning = (float)($diagnostic->addon_cleaning_cost ?? 0);
            $addonRepasta = (float)($diagnostic->addon_repasta_cost ?? 0);
            $netTotal = $labor + $parts + $addonCleaning + $addonRepasta;

            $diagnostic->labor_cost = $labor;
            $diagnostic->parts_cost = $parts;
            $diagnostic->net_total_cost = $netTotal;
            $diagnostic->admin_notes = $validated['admin_notes'];
            $diagnostic->save();

            if ($order->invoice) {
                $order->invoice->update(['amount_total' => $netTotal]);
            }

            $order->update([
                'negotiation_status' => 'Approved',
                'order_status' => 'Approval Biaya',
            ]);
        } else {
            $diagnostic->admin_notes = $validated['admin_notes'];
            $diagnostic->save();

            $order->update([
                'negotiation_status' => 'Rejected',
            ]);
        }

        NotificationService::notifyNegotiationResolved($order, $isApproved);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $isApproved ? 'Negosiasi biaya disetujui dan harga diperbarui.' : 'Negosiasi ditolak. Customer dinotifikasi via WA & Email.',
                'ticket' => $order->fresh(['diagnostic', 'invoice']),
            ]);
        }

        return back()->with('success', $isApproved ? 'Negosiasi disetujui & harga disesuaikan.' : 'Negosiasi ditolak dengan catatan.');
    }

    public function triggerSla(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with('invoice.warranty')
            ->firstOrFail();

        $past = Carbon::now()->subHours(25);
        $order->update([
            'unit_handed_over' => true,
            'handed_over_at' => $past,
            'sla_auto_close_at' => $past->copy()->addHours(24),
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
                'message' => "SLA 1x24 Jam Lewat: Tiket otomatis ditutup (Auto-Closed) dan e-Warranty {$warrantyCode} resmi diaktifkan!",
                'ticket' => $order->fresh(['invoice.warranty']),
            ]);
        }

        return back()->with('success', 'Simulasi SLA 1x24 jam auto-close berhasil dijalankan.');
    }
}
