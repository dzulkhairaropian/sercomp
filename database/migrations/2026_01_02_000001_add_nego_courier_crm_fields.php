<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->enum('negotiation_status', ['None', 'Requested', 'Approved', 'Rejected'])->default('None')->after('order_status');
            $table->text('negotiation_notes')->nullable()->after('negotiation_status');
            $table->decimal('negotiation_budget', 12, 2)->nullable()->after('negotiation_notes');
            $table->string('courier_name', 100)->nullable()->after('sla_auto_close_at');
            $table->string('receiver_name', 100)->nullable()->after('courier_name');
            $table->longText('signature_data')->nullable()->after('receiver_name');
        });

        Schema::table('diagnostics', function (Blueprint $table) {
            $table->foreignId('estimated_by')->nullable()->after('service_order_id')->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable()->after('action_plan');
            $table->boolean('is_price_locked')->default(false)->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('diagnostics', function (Blueprint $table) {
            $table->dropConstrainedForeignId('estimated_by');
            $table->dropColumn(['admin_notes', 'is_price_locked']);
        });

        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn([
                'negotiation_status',
                'negotiation_notes',
                'negotiation_budget',
                'courier_name',
                'receiver_name',
                'signature_data',
            ]);
        });
    }
};
