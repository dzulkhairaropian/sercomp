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

    public function test_stranger_cannot_approve_or_reject_ticket_without_customer_phone_verification(): void
    {
        // Buat customer & order
        $customer = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@gmail.com',
            'phone' => '081233445566',
            'password' => bcrypt('secret123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $order = ServiceOrder::create([
            'customer_id' => $customer->id,
            'ticket_no' => 'SC-2026-7788',
            'device_type' => 'Laptop',
            'device_brand' => 'Asus',
            'device_model' => 'Zenbook',
            'issue_chronology' => 'Thermal throttling',
            'order_status' => 'Approval Biaya',
            'security_pin' => '4829',
            'unit_handed_over' => false,
        ]);

        // Orang asing coba approve tanpa PIN / nomor telepon -> DICEGAT 403
        $unauthorizedApprove = $this->postJson("/user-panel/{$order->ticket_no}/approve");
        $unauthorizedApprove->assertStatus(403);
        $unauthorizedApprove->assertJson(['require_verification' => true]);

        // Orang asing coba tebak PIN salah -> DICEGAT 403
        $wrongPinApprove = $this->postJson("/user-panel/{$order->ticket_no}/approve", ['security_pin' => '9999']);
        $wrongPinApprove->assertStatus(403);

        // Pemilik asli memasukkan 4 kode PIN acak yang benar (4829) -> BERHASIL
        $legitApprove = $this->postJson("/user-panel/{$order->ticket_no}/approve", ['security_pin' => '4829']);
        $legitApprove->assertStatus(200);
        $legitApprove->assertJson(['success' => true]);
    }

    public function test_service_order_cancellation_flow(): void
    {
        $customer = User::create([
            'name' => 'Dewi Sartika',
            'email' => 'dewi.sartika@gmail.com',
            'phone' => '087811223344',
            'password' => bcrypt('secret123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $order = ServiceOrder::create([
            'customer_id' => $customer->id,
            'ticket_no' => 'SC-2026-9911',
            'device_type' => 'Laptop',
            'device_brand' => 'Acer',
            'device_model' => 'Swift 3',
            'issue_chronology' => 'Engsel patah dan layar goyang',
            'order_status' => 'Approval Biaya',
            'unit_handed_over' => false,
        ]);

        $invoice = Invoice::create([
            'service_order_id' => $order->id,
            'invoice_no' => 'INV-SC-2026-9911',
            'amount_total' => 350000,
            'payment_status' => 'Unpaid',
        ]);

        // Konsumen menolak rincian biaya dengan verifikasi 4 digit nomor HP
        $rejectResponse = $this->postJson("/user-panel/{$order->ticket_no}/reject", [
            'phone_verification' => '3344',
        ]);

        $rejectResponse->assertStatus(200);
        $rejectResponse->assertJson(['success' => true]);

        $order->refresh();
        $invoice->refresh();

        // Status order berubah menjadi Dibatalkan
        $this->assertEquals('Dibatalkan', $order->order_status);
        // Invoice menjadi Cancelled dan tagihan dinolkan (Rp 0)
        $this->assertEquals('Cancelled', $invoice->payment_status);
        $this->assertEquals(0, $invoice->amount_total);
        // Garansi tidak boleh terbit
        $this->assertNull($invoice->warranty);
    }

    public function test_booking_without_email_and_with_pickup_service_creates_order_cleanly(): void
    {
        $payload = [
            'device_type' => 'Laptop',
            'device_brand' => 'HP',
            'device_model' => 'Envy 13',
            'issue_chronology' => 'Layar bergaris dan kipas berbunyi keras',
            'delivery_method' => 'Ambil oleh Teknisi',
            'customer_name' => 'Kareem Higgins',
            'customer_phone' => '+1 (143) 741-9991',
        ];

        $response = $this->postJson('/booking', $payload);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $order = ServiceOrder::where('device_brand', 'HP')->where('device_model', 'Envy 13')->first();
        $this->assertNotNull($order);
        $this->assertNotNull($order->security_pin);
        $this->assertEquals(4, strlen($order->security_pin));
        $this->assertNotNull($order->customer);
        $this->assertEquals('Kareem Higgins', $order->customer->name);
    }

    public function test_customer_negotiation_and_admin_resolution_flow(): void
    {
        $customer = User::create([
            'name' => 'Ferry Salim',
            'phone' => '081234556677',
            'email' => 'ferry@salim.id',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $order = ServiceOrder::create([
            'customer_id' => $customer->id,
            'ticket_no' => 'SC-2026-7788',
            'security_pin' => '5566',
            'device_type' => 'Laptop',
            'device_brand' => 'Dell',
            'device_model' => 'XPS 13',
            'issue_chronology' => 'Baterai kembung dan touchpad terangkat',
            'order_status' => 'Approval Biaya',
        ]);

        $order->diagnostic()->create([
            'labor_cost' => 150000,
            'parts_cost' => 650000,
            'net_total_cost' => 800000,
        ]);

        $order->invoice()->create([
            'invoice_no' => 'INV-SC-2026-7788',
            'amount_total' => 800000,
            'payment_status' => 'Unpaid',
        ]);

        // 1. Customer ajukan negosiasi biaya
        $negoResponse = $this->postJson("/user-panel/{$order->ticket_no}/negotiate", [
            'security_pin' => '5566',
            'negotiation_notes' => 'Bisa diskon biaya jasa Rp 50.000 menjadi Rp 750.000?',
            'negotiation_budget' => 750000,
        ]);

        $negoResponse->assertStatus(200);
        $negoResponse->assertJson(['success' => true]);

        $order->refresh();
        $this->assertEquals('Requested', $order->negotiation_status);
        $this->assertEquals(750000, (float)$order->negotiation_budget);

        // 2. Admin meninjau & menyetujui negosiasi
        $admin = User::create([
            'name' => 'Workshop Admin',
            'email' => 'admin_test@sercomp.id',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $resolveResponse = $this->actingAs($admin)->postJson("/admin/meja-kerja/{$order->ticket_no}/nego", [
            'decision' => 'approve',
            'adjusted_labor' => 100000,
            'adjusted_parts' => 650000,
            'admin_notes' => 'Disetujui diskon jasa Rp 50.000.',
        ]);

        $resolveResponse->assertStatus(200);
        $resolveResponse->assertJson(['success' => true]);

        $order->refresh();
        $this->assertEquals('Approved', $order->negotiation_status);
        $this->assertEquals(750000, (float)$order->diagnostic->net_total_cost);
        $this->assertEquals(750000, (float)$order->invoice->amount_total);
    }

    public function test_courier_handover_with_digital_signature(): void
    {
        $admin = User::create([
            'name' => 'Staff Workshop',
            'email' => 'staff_handover@sercomp.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $order = ServiceOrder::create([
            'ticket_no' => 'SC-2026-4455',
            'security_pin' => '1122',
            'device_type' => 'PC Desktop',
            'device_brand' => 'Custom',
            'device_model' => 'Ryzen 7',
            'issue_chronology' => 'Power supply korslet',
            'order_status' => 'Selesai',
        ]);

        $signatureDummy = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $response = $this->actingAs($admin)->postJson("/admin/meja-kerja/{$order->ticket_no}/handover", [
            'courier_name' => 'Rahmat Kurir Kilat',
            'receiver_name' => 'Ibu Maria (Istri Pelanggan)',
            'signature_data' => $signatureDummy,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $order->refresh();
        $this->assertEquals('Diserahkan', $order->order_status);
        $this->assertTrue($order->unit_handed_over);
        $this->assertEquals('Rahmat Kurir Kilat', $order->courier_name);
        $this->assertEquals('Ibu Maria (Istri Pelanggan)', $order->receiver_name);
        $this->assertEquals($signatureDummy, $order->signature_data);
    }

    public function test_separate_admin_and_technician_dashboards(): void
    {
        $admin = User::create([
            'name' => 'Head Admin',
            'email' => 'head_admin@sercomp.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $technician = User::create([
            'name' => 'Teknisi Joko',
            'email' => 'joko@sercomp.id',
            'password' => bcrypt('password123'),
            'role' => 'technician',
            'is_active' => true,
        ]);

        // Admin dashboard
        $adminRes = $this->actingAs($admin)->get('/admin');
        $adminRes->assertStatus(200);
        $adminRes->assertSee('Dashboard KPI');

        // Technician dashboard
        $techRes = $this->actingAs($technician)->get('/teknisi');
        $techRes->assertStatus(200);
        $techRes->assertSee('Dashboard Teknisi Workshop');

        // CRM view
        $crmRes = $this->actingAs($admin)->get('/admin/crm');
        $crmRes->assertStatus(200);
        $crmRes->assertSee('Customer Relationship Management');

        // BI Export CSV
        $exportRes = $this->actingAs($admin)->get('/admin/export-bi');
        $exportRes->assertStatus(200);
        $this->assertTrue($exportRes->headers->contains('content-type', 'text/csv; charset=UTF-8'));
    }

    public function test_personal_and_company_customer_distinction(): void
    {
        // 1. Booking sebagai Company / Korporasi
        $companyBookingPayload = [
            'device_type' => 'PC Desktop',
            'device_brand' => 'Dell Optiplex',
            'device_model' => '7090 Micro',
            'issue_chronology' => 'Unit PC kantor tidak menyala pasca mati lampu',
            'delivery_method' => 'Diantar Sendiri',
            'customer_name' => 'Bpk. Hendra Gunawan (IT Manager)',
            'customer_phone' => '081288990011',
            'customer_email' => 'it.procurement@megacorp.co.id',
            'customer_type' => 'Company',
            'company_name' => 'PT Mega Solusi Nusantara',
        ];

        $response = $this->postJson('/booking', $companyBookingPayload);
        $response->assertStatus(200);

        $order = ServiceOrder::where('ticket_no', $response->json('ticket_no'))->first();
        $this->assertNotNull($order);
        $this->assertEquals('Company', $order->customer_type);
        $this->assertEquals('PT Mega Solusi Nusantara', $order->company_name);

        $customer = $order->customer;
        $this->assertNotNull($customer);
        $this->assertEquals('Company', $customer->customer_type);
        $this->assertEquals('PT Mega Solusi Nusantara', $customer->company_name);

        // 2. Cek tampil di CRM dengan filter Company
        $admin = User::create([
            'name' => 'Admin CRM',
            'email' => 'crm_admin@sercomp.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $crmResponse = $this->actingAs($admin)->get('/admin/crm?type=Company');
        $crmResponse->assertStatus(200);
        $crmResponse->assertSee('PT Mega Solusi Nusantara');
        $crmResponse->assertSee('Company');
    }

    public function test_technician_cannot_access_admin_routes_and_only_sees_assigned_orders(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@sercomp.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $tech1 = User::create([
            'name' => 'Teknisi 1',
            'email' => 'tech1@sercomp.id',
            'tech_code' => 'TEK-01',
            'password' => bcrypt('password123'),
            'role' => 'technician',
            'is_active' => true,
        ]);

        $tech2 = User::create([
            'name' => 'Teknisi 2',
            'email' => 'tech2@sercomp.id',
            'tech_code' => 'TEK-02',
            'password' => bcrypt('password123'),
            'role' => 'technician',
            'is_active' => true,
        ]);

        // Teknisi dilarang mengakses halaman khusus admin (/admin, /admin/crm)
        $techForbiddenRes = $this->actingAs($tech1)->get('/admin');
        $techForbiddenRes->assertRedirect(route('teknisi.dashboard'));

        $techCrmRes = $this->actingAs($tech1)->get('/admin/crm');
        $techCrmRes->assertRedirect(route('teknisi.dashboard'));

        // Buat 2 order: 1 di-assign ke tech1, 1 lagi ke tech2
        $order1 = ServiceOrder::create([
            'technician_id' => $tech1->id,
            'ticket_no' => 'SC-2026-T101',
            'device_type' => 'Laptop',
            'device_brand' => 'Asus',
            'device_model' => 'ROG Strix',
            'issue_chronology' => 'Thermal throttling parah',
            'order_status' => 'Di Workshop',
        ]);

        $order2 = ServiceOrder::create([
            'technician_id' => $tech2->id,
            'ticket_no' => 'SC-2026-T202',
            'device_type' => 'PC Desktop',
            'device_brand' => 'Custom',
            'device_model' => 'Ryzen 7',
            'issue_chronology' => 'No Display',
            'order_status' => 'Di Workshop',
        ]);

        // tech1 hanya melihat SC-2026-T101 di daftar servisnya, dan TIDAK melihat SC-2026-T202
        $listRes = $this->actingAs($tech1)->get('/teknisi/servis');
        $listRes->assertStatus(200);
        $listRes->assertSee('SC-2026-T101');
        $listRes->assertDontSee('SC-2026-T202');

        // Admin bisa meng-assign teknisi
        $assignRes = $this->actingAs($admin)->post("/admin/meja-kerja/{$order2->ticket_no}/assign", [
            'technician_id' => $tech1->id,
        ]);
        $assignRes->assertRedirect();

        $order2->refresh();
        $this->assertEquals($tech1->id, $order2->technician_id);
    }
}
