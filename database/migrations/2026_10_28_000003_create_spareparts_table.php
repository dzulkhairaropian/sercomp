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
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            $table->string('part_code')->unique(); // e.g. FAN-ASUS-G15, TH-ARCTIC-MX4, SSD-NVME-512
            $table->string('name');
            $table->string('category')->default('hardware'); // cooling, chemical, storage, memory, input_device, display, power
            $table->integer('stock')->default(0);
            $table->integer('cost_price')->default(0); // Harga modal beli
            $table->integer('selling_price')->default(0); // Harga jual konsumen
            $table->integer('min_stock_alert')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
