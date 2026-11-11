<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Audit Trail & Stage Timestamps: ticket_status_histories
        Schema::create('ticket_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->string('status_from', 50)->nullable();
            $table->string('status_to', 50);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
        });

        // 2. Harga Modal (Cost Price) pada spareparts dan order_items untuk menghitung Margin Profit di BI
        Schema::table('spareparts', function (Blueprint $table) {
            $table->decimal('cost_price', 12, 2)->default(0)->after('unit_price');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('cost_price', 12, 2)->default(0)->after('unit_price');
            $table->decimal('margin_total', 12, 2)->default(0)->after('subtotal');
        });

        // 3. Kolom Tambahan Analitik BI di service_orders & diagnostics:
        // symptom, fault_category, serial_number, branch_code, lead_source, handover_method, crm_segment_snapshot
        Schema::table('service_orders', function (Blueprint $table) {
            $table->string('serial_number', 80)->nullable()->after('device_model');
            $table->string('branch_code', 30)->default('JKT-PST-01')->after('delivery_method');
            $table->string('lead_source', 50)->default('Website Direct')->after('branch_code'); // Google Ads, Instagram, Walk-in, Website Direct, Corporate B2B
            $table->string('handover_method', 50)->nullable()->after('courier_name'); // 'Pickup Counter', 'Courier Delivery', 'On-site Handover'
            $table->string('crm_segment_snapshot', 50)->nullable()->after('order_status'); // 'Leads', 'Aktif', 'Setia / High Value'
            $table->unsignedTinyInteger('rating_score')->nullable()->after('signature_data'); // 1 - 5
            $table->text('feedback_comments')->nullable()->after('rating_score');
            $table->foreignId('parent_service_order_id')->nullable()->constrained('service_orders')->nullOnDelete()->after('id'); // Tracking Rework / Garansi Re-entry
        });

        Schema::table('diagnostics', function (Blueprint $table) {
            $table->string('symptom_category', 80)->nullable()->after('physical_damage_analysis'); // Power Failure, Thermal/Overheat, Display Artifact, Storage Crash, Fluid Spill
            $table->string('fault_category', 80)->nullable()->after('symptom_category'); // IC Power / Mosfet, Cooling Hardware, Panel Substrate, Storage Sector, Fluid Corrosion
        });

        // 4. Pembayaran Multi-transaksi (DP / Pelunasan)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('payment_reference', 64)->unique();
            $table->enum('payment_type', ['Down Payment (DP)', 'Full Payment', 'Final Settlement (Pelunasan)'])->default('Full Payment');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 50)->default('QRIS Dinamis');
            $table->enum('status', ['Pending', 'Success', 'Failed'])->default('Success');
            $table->timestamp('paid_at')->useCurrent();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');

        Schema::table('diagnostics', function (Blueprint $table) {
            $table->dropColumn(['symptom_category', 'fault_category']);
        });

        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropForeign(['parent_service_order_id']);
            $table->dropColumn([
                'parent_service_order_id',
                'serial_number',
                'branch_code',
                'lead_source',
                'handover_method',
                'crm_segment_snapshot',
                'rating_score',
                'feedback_comments',
            ]);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'margin_total']);
        });

        Schema::table('spareparts', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });

        Schema::dropIfExists('ticket_status_histories');
    }
};
