<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            $table->string('part_code', 50)->unique();
            $table->string('part_name');
            $table->enum('category', ['Fan', 'RAM', 'SSD', 'Screen', 'PSU', 'Cable']);
            $table->unsignedInteger('stock_qty')->default(0);
            $table->decimal('unit_price', 12, 2);
            $table->timestamps();
        });

        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ticket_no', 32)->unique();
            $table->string('security_pin', 10)->default('1234');
            $table->enum('device_type', ['Laptop', 'PC Desktop']);
            $table->string('device_brand', 80);
            $table->string('device_model', 120);
            $table->text('issue_chronology');
            $table->string('issue_photo_url')->nullable();
            $table->boolean('addon_cleaning')->default(false);
            $table->boolean('addon_repasta')->default(false);
            $table->enum('delivery_method', ['Diantar Sendiri', 'Ambil oleh Teknisi'])->default('Diantar Sendiri');
            $table->text('pickup_address')->nullable();
            $table->enum('order_status', [
                'Booking', 'Di Workshop', 'Approval Biaya', 'Paid', 'Reparasi', 'Selesai', 'Diserahkan', 'Closed', 'Dibatalkan'
            ])->default('Booking');
            $table->boolean('unit_handed_over')->default(false);
            $table->timestamp('handed_over_at')->nullable();
            $table->timestamp('sla_auto_close_at')->nullable();
            $table->timestamps();
        });

        Schema::create('diagnostics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->unique()->constrained('service_orders')->cascadeOnDelete();
            $table->text('physical_damage_analysis')->nullable();
            $table->text('action_plan')->nullable();
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('parts_cost', 12, 2)->default(0);
            $table->decimal('addon_cleaning_cost', 12, 2)->default(0);
            $table->decimal('addon_repasta_cost', 12, 2)->default(0);
            $table->decimal('net_total_cost', 12, 2)->default(0);
            $table->string('evidence_running_test_photo')->nullable();
            $table->string('evidence_replaced_part_photo')->nullable();
            $table->boolean('is_approved_by_user')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->foreignId('sparepart_id')->nullable()->constrained('spareparts')->nullOnDelete();
            $table->string('item_name');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->unique()->constrained('service_orders')->cascadeOnDelete();
            $table->string('invoice_no', 32)->unique();
            $table->decimal('amount_total', 12, 2);
            $table->enum('payment_method', ['QRIS Dinamis'])->default('QRIS Dinamis');
            $table->enum('payment_status', ['Unpaid', 'Paid', 'Expired', 'Cancelled'])->default('Unpaid');
            $table->string('qris_transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->unique()->constrained('invoices')->cascadeOnDelete();
            $table->string('warranty_code', 32)->unique();
            $table->unsignedSmallInteger('warranty_duration_days')->default(90);
            $table->date('starts_at');
            $table->date('expires_at');
            $table->enum('warranty_status', ['Active', 'Claimed', 'Expired'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('diagnostics');
        Schema::dropIfExists('service_orders');
        Schema::dropIfExists('spareparts');
    }
};
