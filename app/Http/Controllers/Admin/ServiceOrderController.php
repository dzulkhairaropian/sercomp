<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnostic;
use App\Models\OrderItem;
use App\Models\ServiceOrder;
use App\Models\Sparepart;
use App\Models\Warranty;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'invoice'])->latest();

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

        $countAll = ServiceOrder::count();
        $countLaptop = ServiceOrder::where('device_type', 'Laptop')->count();
        $countPc = ServiceOrder::where('device_type', 'PC Desktop')->count();
        $countApproval = ServiceOrder::where('order_status', 'Approval Biaya')->count();
        $countProgress = ServiceOrder::where('order_status', 'Reparasi')->count();
        $countCompleted = ServiceOrder::whereIn('order_status', ['Selesai', 'Diserahkan', 'Closed'])->count();
        $countCancelled = ServiceOrder::where('order_status', 'Dibatalkan')->count();

        return view('admin.service-list', compact(
            'tickets',
            'countAll',
            'countLaptop',
            'countPc',
            'countApproval',
            'countProgress',
            'countCompleted',
            'countCancelled'
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

        $spareparts = Sparepart::orderBy('part_name')->get();

        return view('admin.workbench', compact('ticket', 'spareparts'));
    }

    public function updateDiagnosis(Request $request, $ticketNo)
    {
        $ticketNoClean = ltrim($ticketNo, '#');
        $order = ServiceOrder::where('ticket_no', $ticketNoClean)
            ->orWhere('ticket_no', '#' . $ticketNoClean)
            ->with(['diagnostic', 'invoice'])
            ->firstOrFail();

        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'action_plan' => 'required|string',
            'cost_labor' => 'required|numeric|min:0',
            'cost_parts' => 'required|numeric|min:0',
            'sparepart_id' => 'nullable|exists:spareparts,id',
        ]);

        $labor = (float) $validated['cost_labor'];
        $parts = (float) $validated['cost_parts'];

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

        // Status beralih ke Approval Biaya (Tahap 3)
        $order->update([
            'order_status' => 'Approval Biaya',
        ]);

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
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit diserahkan ke pelanggan. Timer toleransi uji 1x24 jam aktif!',
                'ticket' => $order,
            ]);
        }

        return back()->with('success', 'Unit diserahkan. SLA timer 24 jam aktif.');
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
