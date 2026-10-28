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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Deep Cleaning & Dust Removal, Repasta Thermal Arctic MX-4
            $table->string('category')->default('maintenance'); // maintenance, hardware, software, custom_pc
            $table->integer('price')->default(0); // in IDR
            $table->text('description')->nullable();
            $table->boolean('is_addon')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
