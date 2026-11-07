<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('customer_type', ['Personal', 'Company'])->default('Personal')->after('role');
            $table->string('company_name', 150)->nullable()->after('customer_type');
        });

        Schema::table('service_orders', function (Blueprint $table) {
            $table->enum('customer_type', ['Personal', 'Company'])->default('Personal')->after('customer_id');
            $table->string('company_name', 150)->nullable()->after('customer_type');
        });
    }

    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn(['customer_type', 'company_name']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['customer_type', 'company_name']);
        });
    }
};
