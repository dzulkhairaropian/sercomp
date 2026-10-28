<?php

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
Route::post('/user-panel/{ticket}/pay-qris', [TrackingController::class, 'payQris'])->name('user.payQris');
Route::post('/user-panel/{ticket}/close', [TrackingController::class, 'closeTicket'])->name('user.close');

Route::get('/invoice/{invoice?}', [InvoiceController::class, 'show'])->name('invoice');

// Workshop Technician & Admin Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/teknisi/login', [AuthController::class, 'showLogin'])->name('teknisi.login');
Route::post('/teknisi/login', [AuthController::class, 'login'])->name('teknisi.login.post');
Route::post('/teknisi/logout', [AuthController::class, 'logout'])->name('teknisi.logout');

// Workshop Technician & Admin Control Panel
Route::prefix('teknisi')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.teknisi');
    Route::get('/servis', [ServiceOrderController::class, 'index'])->name('admin.servis');
    Route::get('/meja-kerja/{ticket?}', [ServiceOrderController::class, 'showWorkbench'])->name('admin.workbench');
    Route::post('/meja-kerja/{ticket}/diagnosis', [ServiceOrderController::class, 'updateDiagnosis'])->name('admin.workbench.diagnosis');
    Route::post('/meja-kerja/{ticket}/complete', [ServiceOrderController::class, 'complete'])->name('admin.workbench.complete');
    Route::post('/meja-kerja/{ticket}/handover', [ServiceOrderController::class, 'handover'])->name('admin.workbench.handover');
    Route::post('/meja-kerja/{ticket}/trigger-sla', [ServiceOrderController::class, 'triggerSla'])->name('admin.workbench.triggerSla');
    
    Route::get('/sparepart', [SparepartController::class, 'index'])->name('admin.sparepart');
    Route::post('/sparepart', [SparepartController::class, 'store'])->name('admin.sparepart.store');
    Route::put('/sparepart/{id}', [SparepartController::class, 'update'])->name('admin.sparepart.update');
});

// Academic Prototype Showcase Portal
Route::get('/portal', function () {
    return view('portal');
})->name('portal');
