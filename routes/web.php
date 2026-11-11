<?php

use App\Http\Controllers\Admin\CustomerCrmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceOrderController;
use App\Http\Controllers\Admin\SparepartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SerComp (Digital Business & Information Systems 2)
|--------------------------------------------------------------------------
*/

// Public & Customer Routes (No Login Friction)
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/booking', [BookingController::class, 'create'])->name('booking');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

Route::get('/user-panel', [TrackingController::class, 'index'])->name('user.panel');
Route::post('/user-panel/{ticket}/approve', [TrackingController::class, 'approve'])->name('user.approve');
Route::post('/user-panel/{ticket}/reject', [TrackingController::class, 'reject'])->name('user.reject');
Route::post('/user-panel/{ticket}/negotiate', [TrackingController::class, 'negotiate'])->name('user.negotiate');
Route::post('/user-panel/{ticket}/pay-qris', [TrackingController::class, 'payQris'])->name('user.payQris');
Route::post('/user-panel/{ticket}/close', [TrackingController::class, 'closeTicket'])->name('user.close');

Route::get('/invoice/{invoice?}', [InvoiceController::class, 'show'])->name('invoice');

// Authentication for Technician & Workshop Staff
Route::get('/login', fn () => redirect()->route('teknisi.login'))->name('login');
Route::get('/admin/login', fn () => redirect()->route('teknisi.login'))->name('admin.login');
Route::get('/teknisi/login', [AuthController::class, 'showLogin'])->name('teknisi.login');
Route::post('/teknisi/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('teknisi.login.post');
Route::post('/teknisi/logout', [AuthController::class, 'logout'])->name('teknisi.logout');

// ==========================================
// 1. ADMIN WORKSHOP CONTROL PANEL
// ==========================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/servis', [ServiceOrderController::class, 'index'])->name('admin.servis.list');
    Route::get('/export-bi', [DashboardController::class, 'exportBiCsv'])->name('admin.bi.export');
    Route::get('/export-bi-alias', [DashboardController::class, 'exportBiCsv'])->name('admin.export.bi');

    // Estimasi & Lembar Persetujuan (Otoritas Admin)
    Route::get('/meja-kerja/{ticket?}', [ServiceOrderController::class, 'showWorkbench'])->name('admin.meja-kerja');
    Route::post('/meja-kerja/{ticket}/assign', [ServiceOrderController::class, 'assignTechnician'])->name('admin.assign.technician');
    Route::post('/meja-kerja/{ticket}/diagnosis', [ServiceOrderController::class, 'updateDiagnosis'])->name('admin.diagnosis.save');
    Route::post('/meja-kerja/{ticket}/nego', [ServiceOrderController::class, 'resolveNegotiation'])->name('admin.nego.resolve');
    Route::post('/meja-kerja/{ticket}/handover', [ServiceOrderController::class, 'handover'])->name('admin.courier.handover');
    Route::post('/meja-kerja/{ticket}/complete', [ServiceOrderController::class, 'complete'])->name('admin.order.complete');

    // Customer Relationship Management (CRM) & Tugas BI
    Route::get('/crm', [CustomerCrmController::class, 'index'])->name('admin.crm');
    Route::post('/crm', [CustomerCrmController::class, 'store'])->name('admin.crm.store');

    // Kasir, Pembayaran di Tempat & Serah Terima Unit
    Route::get('/kasir', [ServiceOrderController::class, 'cashierIndex'])->name('admin.kasir');
    Route::post('/kasir/{ticket}/pay-cash', [ServiceOrderController::class, 'payCashOrPos'])->name('admin.kasir.pay');

    // Inventori Suku Cadang (Kelola Stok & Harga)
    Route::get('/sparepart', [SparepartController::class, 'index'])->name('admin.sparepart.index');
    Route::post('/sparepart', [SparepartController::class, 'store'])->name('admin.sparepart.save');
    Route::put('/sparepart/{id}', [SparepartController::class, 'update'])->name('admin.sparepart.edit');
});

// ==========================================
// 2. TEKNISI WORKSHOP CONTROL PANEL
// ==========================================
Route::prefix('teknisi')->middleware(['auth', 'role:technician,admin'])->group(function () {
    // Dashboard khusus teknisi (fokus operasional unit & perbaikan hardware)
    Route::get('/', [DashboardController::class, 'technicianDashboard'])->name('admin.teknisi');
    Route::get('/dashboard', [DashboardController::class, 'technicianDashboard'])->name('teknisi.dashboard');

    Route::get('/servis', [ServiceOrderController::class, 'index'])->name('admin.servis');
    Route::get('/meja-kerja/{ticket?}', [ServiceOrderController::class, 'showWorkbench'])->name('admin.workbench');
    Route::post('/meja-kerja/{ticket}/diagnosis', [ServiceOrderController::class, 'updateDiagnosis'])->name('admin.workbench.diagnosis');
    Route::post('/meja-kerja/{ticket}/complete', [ServiceOrderController::class, 'complete'])->name('admin.workbench.complete');
    Route::post('/meja-kerja/{ticket}/handover', [ServiceOrderController::class, 'handover'])->name('admin.workbench.handover');
    Route::post('/meja-kerja/{ticket}/trigger-sla', [ServiceOrderController::class, 'triggerSla'])->name('admin.workbench.triggerSla');

    // Teknisi hanya bisa melihat stok katalog part tanpa modifikasi harga
    Route::get('/sparepart', [SparepartController::class, 'index'])->name('admin.sparepart');
});

// Academic Prototype Showcase Portal
Route::get('/portal', function () {
    return view('portal');
})->name('portal');
