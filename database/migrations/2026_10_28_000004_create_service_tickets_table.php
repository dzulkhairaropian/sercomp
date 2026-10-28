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
        Schema::create('service_tickets', function (Blueprint $table) {
            $table->id();
            
            // Nomor Identifikasi Unik Tiket
            $table->string('ticket_no')->unique(); // e.g. #SC-2026-9042

            // Data Pelanggan (Tanpa Kewajiban Akun Login)
            $table->string('customer_name');
            $table->string('customer_phone'); // WhatsApp
            $table->string('customer_email')->nullable();

            // Data Perangkat
            $table->string('device_type'); // Laptop, PC Desktop
            $table->string('device_brand'); // Asus, Lenovo, Acer, HP, Custom Rakitan
            $table->string('device_model')->nullable(); // ROG Strix G15, Vivobook A412DA
            $table->string('device_specs')->nullable();

            // Kronologi & Gejala Kerusakan
            $table->text('issue_chronology');
            $table->string('issue_photo_path')->nullable();

            // Metode Penyerahan Unit & Logistik
            $table->string('delivery_method')->default('Diantar Sendiri'); // Diantar Sendiri, Ambil oleh Teknisi
            $table->text('pickup_address')->nullable();

            // Penugasan & Diagnosa Teknisi
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('diagnosis')->nullable(); // Hasil diagnosa kerusakan fisik
            $table->text('action_plan')->nullable(); // Rencana tindakan perbaikan
            $table->text('technician_notes')->nullable(); // Catatan SOP keamanan data

            // Tahapan Alur & Status Servis
            $table->unsignedTinyInteger('step')->default(1); // 1: Reservasi, 2: Cek Fisik, 3: Approval Biaya, 4: QRIS, 5: Reparasi, 6: Selesai, 7: Closed
            $table->string('status')->default('Menunggu Pemeriksaan'); 
            // Status: Menunggu Pemeriksaan, Menunggu Persetujuan Biaya, Dibatalkan Pelanggan, 
            // Sedang Dikerjakan, Selesai Dikerjakan, Unit Diterima - Menunggu Tutup Tiket, Closed / Completed

            // Rincian Biaya Terkunci di Muka
            $table->integer('cost_labor')->default(0); // Biaya jasa teknisi
            $table->integer('cost_parts')->default(0); // Biaya suku cadang baru
            $table->integer('cost_cleaning')->default(0); // Addon deep cleaning (+50k)
            $table->integer('cost_repasta')->default(0); // Addon repasta Arctic MX-4 (+75k)
            $table->integer('total_cost')->default(0);

            // Transaksi Pembayaran QRIS
            $table->string('payment_status')->default('Unpaid'); // Unpaid, Paid, Refunded
            $table->string('payment_method')->default('QRIS');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();

            // Persetujuan Konsumen (Customer Approval)
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();

            // Dokumentasi Bukti Purnajual
            $table->string('evidence_running_test_path')->nullable(); // Foto uji stabil 68°C
            $table->string('evidence_replaced_part_path')->nullable(); // Foto part lama rusak
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            // Penyerahan Fisik Unit & Logika SLA 1x24 Jam
            $table->boolean('unit_handed_over')->default(false);
            $table->timestamp('unit_handed_over_at')->nullable();
            $table->timestamp('sla_auto_close_deadline')->nullable(); // handed_over_at + 24 jam

            // Penutupan Tiket & Garansi Digital 90 Hari
            $table->boolean('is_closed')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->string('closed_by')->nullable(); // customer, system_sla, technician
            $table->string('warranty_code')->nullable(); // e.g. WRN-2026-8812
            $table->date('warranty_valid_until')->nullable(); // closed_at + 90 hari
            $table->string('invoice_no')->nullable()->unique(); // e.g. INV-SC-2026-9042

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_tickets');
    }
};
