<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\ServiceOrder;
use App\Models\Sparepart;
use App\Models\User;
use App\Models\Warranty;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SercompStateMachineTest extends TestCase
{
    use RefreshDatabase;
    public function test_full_7_stage_service_lifecycle_and_sla_warranty(): void
    {
        // 1. STAGE 1: Guest Booking
        $bookingData = [
            'device_type' => 'Laptop',
            'device_brand' => 'Lenovo',
            'device_model' => 'Legion 5 Pro',
            'issue_chronology' => 'Layar blank setelah overheat saat bermain game.',
            'delivery_method' => 'Diantar Sendiri',
            'customer_name' => 'Bambang Sudirman',
            'customer_phone' => '081299887766',
            'customer_email' => 'bambang@sudirman.id',
            'addon_cleaning' => 1,
            'addon_repasta' => 1,
        ];

        $response = $this->post('/booking', $bookingData);
        $response->assertRedirect();

        $order = ServiceOrder::whereHas('customer', function ($q) {
            $q->where('phone', '081299887766');
        })->first();

        $this->assertNotNull($order);
        $this->assertEquals('Booking', $order->order_status);
        $this->assertNotNull($order->diagnostic);
        $this->assertEquals(50000, $order->diagnostic->addon_cleaning_cost);
        $this->assertEquals(75000, $order->diagnostic->addon_repasta_cost);
        $this->assertNotNull($order->invoice);
        $this->assertEquals('Unpaid', $order->invoice->payment_status);

        // 2. STAGE 2 & 3: Workbench Diagnosis & Approval Biaya
        $technician = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@sercomp.id',
            'tech_code' => 'TEK-2026-01',
            'password' => bcrypt('teknisi123'),
            'role' => 'technician',
            'is_active' => true,
        ]);

        $fanPart = Sparepart::firstOrCreate(
            ['part_code' => 'FAN-ASUS-G15'],
            ['part_name' => 'Modul Fan Asus G15', 'category' => 'Fan', 'stock_qty' => 5, 'unit_price' => 220000]
        );
        $diagnosisPayload = [
            'diagnosis' => 'Fan pendingin macet total dan thermal paste kering mengeras.',
            'action_plan' => 'Ganti modul kipas dan bersihkan saluran pendingin.',
            'cost_labor' => 150000,
            'cost_parts' => 220000,
            'sparepart_id' => $fanPart?->id,
        ];

        $diagResponse = $this->actingAs($technician)->post("/teknisi/meja-kerja/{$order->ticket_no}/diagnosis", $diagnosisPayload);
        $diagResponse->assertRedirect();

        $order->refresh();
        $this->assertEquals('Approval Biaya', $order->order_status);
        $this->assertEquals(150000, $order->diagnostic->labor_cost);
        $this->assertEquals(220000, $order->diagnostic->parts_cost);
        // Total: 150000 + 220000 + 50000 + 75000 = 495000
        $this->assertEquals(495000, $order->diagnostic->net_total_cost);
        $this->assertEquals(495000, $order->invoice->amount_total);

        // 3. STAGE 4 & 5: Customer QRIS Payment -> Reparasi
        $payResponse = $this->post("/user-panel/{$order->ticket_no}/pay-qris");
        $payResponse->assertRedirect();

        $order->refresh();
        $this->assertEquals('Reparasi', $order->order_status);
        $this->assertEquals('Paid', $order->invoice->payment_status);
        $this->assertNotNull($order->invoice->paid_at);

        // 4. STAGE 6: Reparasi Selesai
        $completeResponse = $this->post("/teknisi/meja-kerja/{$order->ticket_no}/complete");
        $completeResponse->assertRedirect();

        $order->refresh();
        $this->assertEquals('Selesai', $order->order_status);

        // 5. STAGE 7: Serah Terima Unit (Handover) -> Timer SLA 24h aktif
        $handoverResponse = $this->post("/teknisi/meja-kerja/{$order->ticket_no}/handover");
        $handoverResponse->assertRedirect();

        $order->refresh();
        $this->assertEquals('Diserahkan', $order->order_status);
        $this->assertTrue($order->unit_handed_over);
        $this->assertNotNull($order->handed_over_at);
        $this->assertNotNull($order->sla_auto_close_at);

        // 6. SLA Auto-Close: Simulasikan 25 jam telah berlalu
        $order->update([
            'sla_auto_close_at' => Carbon::now()->subHour(),
        ]);

        $this->artisan('sercomp:sla-close')
            ->assertSuccessful();

        $order->refresh();
        $this->assertEquals('Closed', $order->order_status);

        // e-Warranty 90 hari otomatis terbit
        $warranty = Warranty::where('invoice_id', $order->invoice->id)->first();
        $this->assertNotNull($warranty);
        $this->assertEquals(90, $warranty->warranty_duration_days);
        $this->assertEquals('Active', $warranty->warranty_status);
        $this->assertStringStartsWith('WAR-', $warranty->warranty_code);
    }

    public function test_unauthorized_guests_and_customers_cannot_access_workshop_routes(): void
    {
        // Guest dicegat middleware auth
        $guestResponse = $this->get('/teknisi');
        $guestResponse->assertRedirect('/teknisi/login');

        // Akun customer dicegat middleware staff
        $customer = User::create([
            'name' => 'Konsumen Biasa',
            'email' => 'konsumen@gmail.com',
            'phone' => '089988776655',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $customerResponse = $this->actingAs($customer)->get('/teknisi');
        $customerResponse->assertRedirect('/teknisi/login');

        // Staf teknisi diizinkan akses
        $tech = User::create([
            'name' => 'Teknisi Resmi',
            'email' => 'teknisi.resmi@sercomp.id',
            'tech_code' => 'TEK-2026-99',
            'password' => bcrypt('password123'),
            'role' => 'technician',
            'is_active' => true,
        ]);

        $techResponse = $this->actingAs($tech)->get('/teknisi');
        $techResponse->assertOk();
    }
}
