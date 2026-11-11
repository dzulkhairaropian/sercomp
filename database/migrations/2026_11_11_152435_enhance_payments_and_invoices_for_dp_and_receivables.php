<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tambah due_date dan estimated revisions pada invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('paid_at'); // Untuk termin B2B (NET 30) & Aging Piutang
            $table->decimal('initial_estimate', 12, 2)->nullable()->after('amount_total'); // Estimasi awal saat diagnosa pertama
            $table->decimal('revised_estimate', 12, 2)->nullable()->after('initial_estimate'); // Estimasi revisi jika ada tambahan part / negosiasi
        });

        // 2. Modifikasi kolom payments:
        // payment_type: 'DP', 'PELUNASAN', 'FULL', 'REFUND', 'DIAGNOSA_FEE'
        // status: 'Pending', 'Success', 'Failed', 'Refunded'
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_type', 40)->default('FULL')->change();
            $table->string('status', 30)->default('Success')->change();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'Success', 'Failed'])->default('Success')->change();
            $table->enum('payment_type', ['Down Payment (DP)', 'Full Payment', 'Final Settlement (Pelunasan)'])->default('Full Payment')->change();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'initial_estimate', 'revised_estimate']);
        });
    }
};
