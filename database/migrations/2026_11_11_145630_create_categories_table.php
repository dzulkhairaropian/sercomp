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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->string('slug', 80)->unique();
            $table->string('type', 30)->default('sparepart'); // 'sparepart', 'device', 'service'
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tambah category_id ke spareparts sebagai foreign key relasional fleksibel
        Schema::table('spareparts', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained('categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
