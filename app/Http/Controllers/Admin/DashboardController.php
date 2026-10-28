<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceTicket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman Dashboard KPI & Ringkasan Workshop.
     */
    public function index()
    {
        $totalOrders = ServiceTicket::count();
        $inAnalysis = ServiceTicket::where('step', '<=', 2)->count();
        $waitingApproval = ServiceTicket::whereIn('status', ['Menunggu Persetujuan Biaya', 'Menunggu Pembayaran QRIS'])->count();
        $inProgress = ServiceTicket::where('status', 'Sedang Dikerjakan')->count();
        $completed = ServiceTicket::whereIn('status', ['Selesai Dikerjakan', 'Unit Diterima - Menunggu Tutup Tiket', 'Closed / Completed'])->count();

        // Total omzet dari tiket lunas
        $totalRevenue = ServiceTicket::where('payment_status', 'Paid')->sum('total_cost');

        // Unit aktif prioritas di meja kerja
        $activeTicket = ServiceTicket::with(['technician', 'items'])
            ->where('ticket_no', '#SC-2026-9042')
            ->first() ?? ServiceTicket::with(['technician', 'items'])->latest()->first();

        // 5 Antrean unit terbaru
        $recentTickets = ServiceTicket::latest()->take(5)->get();

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
}
