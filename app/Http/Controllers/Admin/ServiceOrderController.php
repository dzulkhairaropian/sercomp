<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceTicket;
use App\Models\SlaLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceOrderController extends Controller
{
    /**
     * Tampilkan daftar seluruh unit servis (Laptop & PC).
     */
    public function index(Request $request)
    {
        $query = ServiceTicket::with(['technician', 'items'])->latest();

        // Filter berdasarkan jenis perangkat
        if ($request->filled('device')) {
            $query->where('device_type', $request->device);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }

        // Pencarian teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('device_brand', 'like', "%{$search}%")
                  ->orWhere('device_model', 'like', "%{$search}%");
            });
        }

        $tickets = $query->get();

        // Hitungan per kategori untuk filter badge
        $countAll = ServiceTicket::count();
        $countLaptop = ServiceTicket::where('device_type', 'Laptop')->count();
        $countPc = ServiceTicket::where('device_type', 'PC Desktop')->count();
        $countApproval = ServiceTicket::where('status', 'like', '%Approval%')->count();
        $countProgress = ServiceTicket::where('status', 'Sedang Dikerjakan')->count();
        $countCompleted = ServiceTicket::whereIn('status', ['Selesai Dikerjakan', 'Closed / Completed'])->count();

        return view('admin.service-list', compact(
            'tickets',
            'countAll',
            'countLaptop',
            'countPc',
            'countApproval',
            'countProgress',
            'countCompleted'
        ));
    }

    /**
     * Tampilkan meja kerja diagnosa aktif untuk tiket tertentu.
     */
    public function showWorkbench(Request $request, $ticketNo = null)
    {
        $ticketNo = $ticketNo ?? $request->query('ticket');

        if ($ticketNo) {
            $ticket = ServiceTicket::with(['technician', 'items'])
                ->where('ticket_no', $ticketNo)
                ->orWhere('ticket_no', '#' . ltrim($ticketNo, '#'))
                ->first();
        } else {
            $ticket = ServiceTicket::with(['technician', 'items'])
                ->where('ticket_no', '#SC-2026-9042')
                ->first() ?? ServiceTicket::with(['technician', 'items'])->latest()->first();
        }

        return view('admin.workbench', compact('ticket'));
    }

    /**
     * Teknisi memperbarui lembar persetujuan diagnosa fisik & estimasi biaya.
     */
    public function updateDiagnosis(Request $request, $ticketNo)
    {
        $ticket = ServiceTicket::where('ticket_no', $ticketNo)->firstOrFail();

        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'action_plan' => 'required|string',
            'cost_labor' => 'required|numeric|min:0',
            'cost_parts' => 'required|numeric|min:0',
            'technician_notes' => 'nullable|string',
        ]);

        $labor = (int) $validated['cost_labor'];
        $parts = (int) $validated['cost_parts'];
        $addons = $ticket->cost_cleaning + $ticket->cost_repasta;
        $totalCost = $labor + $parts + $addons;

        $ticket->update([
            'diagnosis' => $validated['diagnosis'],
            'action_plan' => $validated['action_plan'],
            'cost_labor' => $labor,
            'cost_parts' => $parts,
            'total_cost' => $totalCost,
            'technician_notes' => $validated['technician_notes'] ?? null,
            'step' => 3,
            'status' => 'Menunggu Persetujuan Biaya',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lembar persetujuan berhasil diperbarui dan disinkronkan ke portal pelanggan!',
                'ticket' => $ticket,
            ]);
        }

        return back()->with('success', 'Lembar diagnosa dan persetujuan biaya berhasil disimpan.');
    }

    /**
     * Teknisi menandai reparasi selesai dan siap diserahkan.
     */
    public function complete(Request $request, $ticketNo)
    {
        $ticket = ServiceTicket::where('ticket_no', $ticketNo)->firstOrFail();

        $ticket->update([
            'is_completed' => true,
            'completed_at' => Carbon::now(),
            'step' => 6,
            'status' => 'Selesai Dikerjakan',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Servis unit ditandai selesai! Bukti terlampir dan invoice siap dicetak.',
                'ticket' => $ticket,
            ]);
        }

        return back()->with('success', 'Servis unit selesai dikerjakan.');
    }

    /**
     * Teknisi menyerahkan fisik unit ke pelanggan (memulai timer SLA 1x24 jam).
     */
    public function handover(Request $request, $ticketNo)
    {
        $ticket = ServiceTicket::where('ticket_no', $ticketNo)->firstOrFail();

        $ticket->update([
            'unit_handed_over' => true,
            'unit_handed_over_at' => Carbon::now(),
            'sla_auto_close_deadline' => Carbon::now()->addHours(24),
            'step' => 6,
            'status' => 'Unit Diterima - Menunggu Tutup Tiket',
        ]);

        SlaLog::create([
            'service_ticket_id' => $ticket->id,
            'event_type' => 'handed_over',
            'description' => 'Unit diserahkan fisik kepada pelanggan. Penghitungan mundur masa uji SLA 1x24 jam dimulai.',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit fisik telah diserahkan. Timer toleransi uji 1x24 jam aktif!',
                'ticket' => $ticket,
            ]);
        }

        return back()->with('success', 'Unit berhasil diserahterimakan ke konsumen.');
    }

    /**
     * Simulasi otomatisasi SLA 1x24 jam habis tanpa komplain (Auto-Closed).
     */
    public function triggerSla(Request $request, $ticketNo)
    {
        $ticket = ServiceTicket::where('ticket_no', $ticketNo)->firstOrFail();

        $warrantyCode = 'WRN-' . date('Y') . '-' . mt_rand(1000, 9999);

        $ticket->update([
            'unit_handed_over' => true,
            'unit_handed_over_at' => Carbon::now()->subHours(25),
            'is_closed' => true,
            'closed_at' => Carbon::now(),
            'closed_by' => 'system_sla',
            'step' => 7,
            'status' => 'Closed / Completed',
            'warranty_code' => $warrantyCode,
            'warranty_valid_until' => Carbon::now()->addDays(90),
        ]);

        SlaLog::create([
            'service_ticket_id' => $ticket->id,
            'event_type' => 'auto_closed',
            'description' => 'Masa uji 1x24 jam pasca-terima terlampaui. Scheduler menutup tiket secara otomatis dan mengaktifkan e-Warranty 90 hari.',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "SLA 1x24 Jam Lewat: Tiket otomatis ditutup (Auto-Closed) dan e-Warranty {$warrantyCode} resmi diaktifkan!",
                'ticket' => $ticket,
            ]);
        }

        return back()->with('success', 'Simulasi SLA 1x24 jam auto-close berhasil dijalankan.');
    }
}
