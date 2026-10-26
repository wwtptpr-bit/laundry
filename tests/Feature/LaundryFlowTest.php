<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaundryFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * 1. TEST FITUR LANDING PAGE (PUBLIC DASHBOARD)
     */
    public function test_landing_page_loads_with_brand_and_active_packages(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CROWN &amp; LUCKY', false);
        $response->assertSee('Paket Reguler');
        $response->assertSee('Paket VIP');
        $response->assertSee('Paket VVIP');
        $response->assertSee('Paket Prioritas');
        $response->assertSee('GORDYN SPECIALIST');
    }

    public function test_public_order_submission_and_tracking(): void
    {
        $payload = [
            'name' => 'Budi Santoso Guest',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 10, Jakarta',
            'package_code' => 'prioritas',
            'estimated_weight' => 4.0,
            'notes' => 'Tolong jemput sebelum jam 12 siang',
        ];

        $response = $this->post('/order/submit', $payload);

        $order = Order::where('customer_phone', '081234567890')->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('menunggu_penjemputan', $order->status);
        $this->assertEquals('prioritas', $order->package_code);
        $this->assertEquals(4, $order->priority_level);

        $response->assertRedirect(route('public.track', ['code' => $order->order_code]));

        // Check tracking page
        $trackResponse = $this->get(route('public.track', ['code' => $order->order_code]));
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($order->order_code);
        $trackResponse->assertSee('Paket Prioritas');
        $trackResponse->assertSee('Menunggu Penjemputan');
    }

    /**
     * 2. TEST AUTHENTICATION (5 ROLES & NO QUICK LOGIN)
     */
    public function test_login_page_renders_cleanly_without_quick_login(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Dashboard');
        $response->assertSee('Alamat Email Akun');
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        // Ensure quick-login route and buttons are not present
        $response->assertDontSee('/quick-login/');
    }

    public function test_login_authentication_and_redirection_for_all_five_roles(): void
    {
        $roles = [
            'owner@crownlucky.com' => '/owner',
            'admin@crownlucky.com' => '/admin',
            'supir@crownlucky.com' => '/supir',
            'operator@crownlucky.com' => '/operator',
            'customer@gmail.com' => '/customer',
        ];

        foreach ($roles as $email => $expectedRedirectPath) {
            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'password',
            ]);

            $response->assertRedirect($expectedRedirectPath);
            $this->assertAuthenticated();

            // Logout to prepare for next role
            $this->post('/logout');
            $this->assertGuest();
        }
    }

    /**
     * 3. TEST FITUR OWNER: UBAH HARGA & MONITORING 4 ROLE
     */
    public function test_owner_dashboard_and_price_update_reflects_on_public_landing(): void
    {
        $owner = User::where('role', 'owner')->first();
        $this->actingAs($owner);

        // Owner views dashboard
        $response = $this->get('/owner');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Owner');

        // Owner views prices page
        $pricesResponse = $this->get('/owner/prices');
        $pricesResponse->assertStatus(200);
        $pricesResponse->assertSee('Kelola Harga Paket Layanan Publik');
        $pricesResponse->assertSee('Fitur Eksklusif Owner');

        // Owner updates price of Paket Reguler from 6000 to 7500
        $pkgReguler = Package::where('code', 'reguler')->first();
        $updateResponse = $this->post(route('owner.prices.update', $pkgReguler->id), [
            'name' => 'Paket Reguler Super Clean',
            'price_per_kg' => 7500,
            'estimated_hours' => 48,
            'features_text' => "Selesai 2-3 hari\nCuci bersih wangi\nSetrika rapi",
            'is_active' => '1',
        ]);

        $updateResponse->assertSessionHas('success');

        // Verify database is updated
        $pkgReguler->refresh();
        $this->assertEquals(7500, $pkgReguler->price_per_kg);
        $this->assertEquals('Paket Reguler Super Clean', $pkgReguler->name);

        // Verify public landing page now displays the updated price
        $landingResponse = $this->get('/');
        $landingResponse->assertSee('Paket Reguler Super Clean');
        $landingResponse->assertSee('7.500');

        // Owner views monitoring page (monitoring activities of other 4 roles)
        $monitoringResponse = $this->get('/owner/monitoring');
        $monitoringResponse->assertStatus(200);
        $monitoringResponse->assertSee('Monitoring Aktivitas 4 Role Lainnya');
    }

    /**
     * 4. TEST FITUR ADMIN: MIRROR DARI OWNER & RESTRIKSI READ-ONLY
     */
    public function test_admin_views_dashboard_and_cannot_modify_prices(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // Admin views dashboard and mirrors owner view
        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Akun Admin (Mode Pantau / Read-Only)');
        $response->assertSee('Mode Akses: Read-Only');

        // Admin views prices page (read-only mode)
        $pricesResponse = $this->get('/admin/prices');
        $pricesResponse->assertStatus(200);
        $pricesResponse->assertSee('Mode Admin: Hanya Pantau (Read-Only)');
        $pricesResponse->assertSee('Hanya Owner yang dapat mengedit');

        // Admin cannot submit price update to owner route
        $pkg = Package::where('code', 'vip')->first();
        $attemptUpdate = $this->post(route('owner.prices.update', $pkg->id), [
            'name' => 'Paket VIP Hacked',
            'price_per_kg' => 1000,
            'estimated_hours' => 12,
        ]);

        // Blocked and redirected to admin dashboard because admin cannot edit prices
        $attemptUpdate->assertRedirect(route('admin.dashboard'));
    }

    /**
     * 5. TEST FITUR SUPIR: JEMPUT, INPUT TOTAL, DROP, NOTIFIKASI & ANTAR (ZERO ERROR!)
     */
    public function test_supir_complete_workflow_pickup_drop_start_delivery_and_delivered(): void
    {
        $supir = User::where('role', 'supir')->first();
        $this->actingAs($supir);

        // Supir views dashboard
        $dashboardResponse = $this->get('/supir');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Antrian Pengantaran');

        // Create a new order waiting for pickup
        $pkg = Package::where('code', 'vvip')->first();
        $order = Order::create([
            'order_code' => 'CLN-TEST-SUPIR-001',
            'customer_name' => 'Ibu Maya',
            'customer_phone' => '081299998888',
            'customer_address' => 'Jl. Senayan No. 4',
            'package_id' => $pkg->id,
            'package_code' => $pkg->code,
            'package_name' => $pkg->name,
            'price_per_kg' => $pkg->price_per_kg,
            'estimated_hours' => 12,
            'priority_level' => 3,
            'weight' => 5.0,
            'total_price' => 5.0 * $pkg->price_per_kg,
            'status' => 'menunggu_penjemputan',
            'estimated_completion_at' => Carbon::now()->addHours(12),
        ]);

        // A. Supir picks up and inputs total item count
        $pickupResponse = $this->post(route('supir.pickup', $order->id), [
            'driver_item_count' => 16,
            'weight' => 5.2,
            'driver_notes' => 'Diambil dari lobby resepsionis',
        ]);

        $pickupResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('telah_diambil', $order->status);
        $this->assertEquals(16, $order->driver_item_count);
        $this->assertEquals(5.2, $order->weight);
        $this->assertNotNull($order->picked_up_at);

        // B. Supir drops laundry at workshop
        $dropResponse = $this->post(route('supir.drop', $order->id));
        $dropResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('siap_dikerjakan', $order->status);
        $this->assertNotNull($order->dropped_at);

        // Simulate Operator finishing the order -> status becomes 'siap_diantar'
        $order->update([
            'status' => 'siap_diantar',
            'actual_completion_at' => Carbon::now(),
        ]);

        // Supir dashboard now shows order in delivery queue
        $supirDash = $this->get('/supir');
        $supirDash->assertSee('CLN-TEST-SUPIR-001');
        $supirDash->assertSee('Mulai Pengantaran');
        $supirDash->assertSee('Telah Diantar (Selesai)');

        // C. Supir presses "Bawa & Mulai Pengantaran" (VERIFY NO 500 ERROR!)
        $startDeliveryResponse = $this->post(route('supir.start-delivery', $order->id));
        $startDeliveryResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('dalam_pengantaran', $order->status);
        $this->assertEquals($supir->id, $order->driver_id);

        // D. Supir presses "Telah Diantar (Selesai)" (VERIFY NO 500 ERROR!)
        $deliverResponse = $this->post(route('supir.deliver', $order->id));
        $deliverResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('telah_diantar', $order->status);
        $this->assertNotNull($order->delivered_at);
    }

    /**
     * 6. TEST FITUR OPERATOR: ANTRIAN PRIORITAS, HITUNG ULANG (SELISIH), PENUNDAAN & SELESAI
     */
    public function test_operator_priority_queue_sorting(): void
    {
        $operator = User::where('role', 'oprator')->first();
        $this->actingAs($operator);

        $response = $this->get('/operator');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Operator Laundry');
        $response->assertSee('Antrian Pengerjaan Laundry Berdasarkan Prioritas Layanan');
    }

    public function test_operator_recount_with_discrepancy_detection(): void
    {
        $operator = User::where('role', 'oprator')->first();
        $this->actingAs($operator);

        $pkg = Package::where('code', 'vip')->first();
        $order = Order::create([
            'order_code' => 'CLN-TEST-DISCREPANCY',
            'customer_name' => 'Bapak Joko',
            'customer_phone' => '081288887777',
            'customer_address' => 'Jl. Gatot Subroto No. 5',
            'package_id' => $pkg->id,
            'package_code' => $pkg->code,
            'package_name' => $pkg->name,
            'price_per_kg' => $pkg->price_per_kg,
            'estimated_hours' => 24,
            'priority_level' => 2,
            'weight' => 4.0,
            'total_price' => 40000,
            'driver_item_count' => 20, // Driver counted 20
            'status' => 'siap_dikerjakan',
            'estimated_completion_at' => Carbon::now()->addHours(24),
        ]);

        // Operator recounts and finds 19 items (1 item missing!)
        $recountResponse = $this->post(route('operator.recount', $order->id), [
            'operator_item_count' => 19,
            'discrepancy_note' => 'Kurang 1 potong kaos oblong',
        ]);

        $recountResponse->assertSessionHas('warning');
        $order->refresh();

        // Driver count is preserved, discrepancy is recorded!
        $this->assertEquals(20, $order->driver_item_count);
        $this->assertEquals(19, $order->operator_item_count);
        $this->assertEquals(-1, $order->item_discrepancy);
        $this->assertTrue($order->has_discrepancy);
    }

    public function test_operator_start_delay_recalculate_eta_resume_and_finish(): void
    {
        $operator = User::where('role', 'oprator')->first();
        $this->actingAs($operator);

        $pkg = Package::where('code', 'reguler')->first();
        $initialEta = Carbon::now()->addHours(48);
        $order = Order::create([
            'order_code' => 'CLN-TEST-OPERATOR-FLOW',
            'customer_name' => 'Sdr. Dimas',
            'customer_phone' => '081377776666',
            'customer_address' => 'Jl. Salemba Raya No. 9',
            'package_id' => $pkg->id,
            'package_code' => $pkg->code,
            'package_name' => $pkg->name,
            'price_per_kg' => $pkg->price_per_kg,
            'estimated_hours' => 48,
            'priority_level' => 1,
            'weight' => 6.0,
            'total_price' => 36000,
            'driver_item_count' => 15,
            'status' => 'siap_dikerjakan',
            'estimated_completion_at' => $initialEta,
        ]);

        // A. Operator starts working
        $startResponse = $this->post(route('operator.start', $order->id));
        $startResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('proses', $order->status);

        // B. Operator delays due to stubborn stain (3 hours delay)
        $delayResponse = $this->post(route('operator.delay', $order->id), [
            'delay_hours' => 3,
            'delay_reason' => 'Perendaman ekstra noda tinta dan karat',
        ]);

        $delayResponse->assertSessionHas('info');
        $order->refresh();
        $this->assertEquals('pending', $order->status);
        $this->assertEquals(180, $order->delay_minutes);
        $this->assertEquals('Perendaman ekstra noda tinta dan karat', $order->delay_reason);
        // Estimated completion is recalculated (+3 hours)
        $this->assertTrue(Carbon::parse($order->estimated_completion_at)->greaterThan($initialEta));

        // C. Operator resumes processing
        $resumeResponse = $this->post(route('operator.resume', $order->id));
        $resumeResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('proses', $order->status);

        // D. Operator finishes processing -> ready for driver delivery
        $finishResponse = $this->post(route('operator.finish', $order->id));
        $finishResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('siap_diantar', $order->status);
        $this->assertNotNull($order->actual_completion_at);
    }

    /**
     * 7. TEST FITUR CUSTOMER: PILIH PAKET, ESTIMASI WAKTU, STATUS LIVE & HISTORY
     */
    public function test_customer_order_creation_with_priority_and_history(): void
    {
        $customer = User::where('role', 'customer')->first();
        $this->actingAs($customer);

        // Customer dashboard
        $dashboardResponse = $this->get('/customer');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Pesanan Aktif');

        // Customer creates an order choosing VVIP package
        $createResponse = $this->post(route('customer.order.store'), [
            'package_code' => 'vvip',
            'weight' => 5.0,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'customer_address' => $customer->address,
            'customer_notes' => 'Tolong parfum yang wangi lembut',
        ]);

        $createResponse->assertRedirect(route('customer.dashboard'));

        $order = Order::where('user_id', $customer->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('Paket VVIP', $order->package_name);
        $this->assertEquals(3, $order->priority_level);
        $this->assertEquals('menunggu_penjemputan', $order->status);

        // Customer history page
        $historyResponse = $this->get(route('customer.history'));
        $historyResponse->assertStatus(200);
        $historyResponse->assertSee('Riwayat Pesanan Selesai');
    }

    /**
     * 8. TEST ROLE-BASED ACCESS CONTROL (SECURITY CHECK)
     */
    public function test_role_based_access_controls(): void
    {
        $customer = User::where('role', 'customer')->first();
        $this->actingAs($customer);

        // Customer cannot access other dashboards, redirected to customer dashboard
        $this->get('/owner')->assertRedirect(route('customer.dashboard'));
        $this->get('/admin')->assertRedirect(route('customer.dashboard'));
        $this->get('/supir')->assertRedirect(route('customer.dashboard'));
        $this->get('/operator')->assertRedirect(route('customer.dashboard'));

        // Guest cannot access protected dashboards
        $this->post('/logout');
        $this->get('/owner')->assertRedirect('/login');
        $this->get('/supir')->assertRedirect('/login');
        $this->get('/operator')->assertRedirect('/login');
    }
}
