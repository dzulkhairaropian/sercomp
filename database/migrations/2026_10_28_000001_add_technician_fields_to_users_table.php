<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('technician')->after('email'); // admin, technician
            $table->string('tech_code')->nullable()->unique()->after('role'); // e.g. TEK-2026-01
            $table->string('phone')->nullable()->after('tech_code');
            $table->string('specialization')->nullable()->after('phone'); // e.g. Hardware Specialist Level 3
            $table->boolean('is_active')->default(true)->after('specialization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'tech_code', 'phone', 'specialization', 'is_active']);
        });
    }
};
