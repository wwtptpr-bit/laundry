<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Users for 5 Roles
        $owner = User::updateOrCreate(
            ['email' => 'owner@crownlucky.com'],
            [
                'name' => 'Bapak Hendra (Owner)',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => '081298765432',
                'address' => 'Jl. Sudirman No. 1, Jakarta Pusat',
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@crownlucky.com'],
            [
                'name' => 'Siti Rahma (Admin Monitoring)',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081345678901',
                'address' => 'Jl. Thamrin No. 45, Jakarta Pusat',
            ]
        );

        $supir = User::updateOrCreate(
            ['email' => 'supir@crownlucky.com'],
            [
                'name' => 'Budi Santoso (Supir Antar-Jemput)',
                'password' => Hash::make('password'),
                'role' => 'supir',
                'phone' => '081765432109',
                'address' => 'Jl. Kebon Jeruk No. 12, Jakarta Barat',
            ]
        );

        $operator = User::updateOrCreate(
            ['email' => 'operator@crownlucky.com'],
            [
                'name' => 'Agus Pratama (Operator Laundry)',
                'password' => Hash::make('password'),
                'role' => 'oprator',
                'phone' => '081987654321',
                'address' => 'Jl. Cempaka Putih No. 8, Jakarta Pusat',
            ]
        );

        $customer = User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Dewi Lestari (Pelanggan)',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '082123456789',
                'address' => 'Apartemen Grand Indonesia Tower A No. 15B',
            ]
        );

        // 2. Create Service Packages (Prices can be updated by Owner)
        $pkgReguler = Package::updateOrCreate(
            ['code' => 'reguler'],
            [
                'name' => 'Paket Reguler',
                'price_per_kg' => 6000,
                'unit' => '/kg',
                'estimated_hours' => 48,
                'priority_level' => 1,
                'description' => 'Pilihan hemat untuk pakaian harian keluarga Anda.',
                'features' => [
                    'Selesai 2-3 hari',
                    'Cuci bersih & wangi khas segar',
                    'Setrika rapi & lipat higienis',
                    'Gratis antar-jemput (min. 5kg)',
                    'Antrian standar reguler',
                ],
                'popular' => false,
                'bg_gradient' => 'from-green-50 to-green-100',
                'badge_text' => null,
                'is_active' => true,
            ]
        );

        $pkgVip = Package::updateOrCreate(
            ['code' => 'vip'],
            [
                'name' => 'Paket VIP',
                'price_per_kg' => 10000,
                'unit' => '/kg',
                'estimated_hours' => 24,
                'priority_level' => 2,
                'description' => 'Layanan cepat satu hari dengan prioritas di atas Reguler.',
                'features' => [
                    'Selesai 24 jam (1 hari)',
                    'Prioritas antrian di atas Reguler',
                    'Cuci higienis deterjen aroma terapi',
                    'Setrika uap rapi bergaransi',
                    'Gratis antar-jemput',
                ],
                'popular' => false,
                'bg_gradient' => 'from-amber-50 to-orange-100',
                'badge_text' => 'CEPAT 24 JAM',
                'is_active' => true,
            ]
        );

        $pkgVvip = Package::updateOrCreate(
            ['code' => 'vvip'],
            [
                'name' => 'Paket VVIP',
                'price_per_kg' => 16000,
                'unit' => '/kg',
                'estimated_hours' => 12,
                'priority_level' => 3,
                'description' => 'Perawatan eksklusif 12 jam dengan deterjen dan pelembut premium.',
                'features' => [
                    'Selesai 12 jam',
                    'Prioritas tinggi di atas VIP & Reguler',
                    'Mesin cuci terpisah per pelanggan',
                    'Setrika uap profesional & hanger rapi',
                    'Parfum premium tahan hingga 2 minggu',
                ],
                'popular' => true,
                'bg_gradient' => 'from-emerald-50 to-emerald-100',
                'badge_text' => 'PALING LARIS',
                'is_active' => true,
            ]
        );

        $pkgPrioritas = Package::updateOrCreate(
            ['code' => 'prioritas'],
            [
                'name' => 'Paket Prioritas',
                'price_per_kg' => 25000,
                'unit' => '/kg',
                'estimated_hours' => 4,
                'priority_level' => 4,
                'description' => 'Prioritas utama mutlak! Dikerjakan seketika tanpa antrean.',
                'features' => [
                    'Selesai super kilat 4 jam',
                    'Dikerjakan paling awal (Top Priority)',
                    'Garansi tanpa menunggu antrean',
                    'Parfum eksklusif kelas hotel bintang 5',
                    'Armada antar-jemput ekspres khusus',
                ],
                'popular' => false,
                'bg_gradient' => 'from-rose-50 to-red-100',
                'badge_text' => 'KILAT PRIORITAS',
                'is_active' => true,
            ]
        );

        // 3. Seed Sample Orders across all workflow stages
        $now = Carbon::now();

        // Order 1: Selesai / Siap Diantar (In Driver's delivery queue)
        $ord1 = Order::create([
            'order_code' => 'CLN-20261023-0101',
            'user_id' => $customer->id,
            'customer_name' => 'Dewi Lestari',
            'customer_phone' => '082123456789',
            'customer_address' => 'Apartemen Grand Indonesia Tower A No. 15B',
            'package_id' => $pkgPrioritas->id,
            'package_code' => $pkgPrioritas->code,
            'package_name' => $pkgPrioritas->name,
            'price_per_kg' => $pkgPrioritas->price_per_kg,
            'estimated_hours' => $pkgPrioritas->estimated_hours,
            'priority_level' => $pkgPrioritas->priority_level,
            'weight' => 4.5,
            'total_price' => 4.5 * $pkgPrioritas->price_per_kg,
            'driver_id' => $supir->id,
            'driver_item_count' => 18,
            'driver_notes' => 'Diambil dari lobby apartemen lantai 1',
            'picked_up_at' => $now->copy()->subHours(5),
            'dropped_at' => $now->copy()->subHours(4),
            'operator_id' => $operator->id,
            'operator_item_count' => 18,
            'item_discrepancy' => 0,
            'discrepancy_note' => 'Jumlah barang cocok (18 potong).',
            'status' => 'siap_diantar',
            'estimated_completion_at' => $now->copy()->subHour(),
            'actual_completion_at' => $now->copy()->subHour(),
            'customer_notes' => 'Tolong hati-hati dengan kemeja sutra putih.',
            'payment_status' => 'paid',
            'created_at' => $now->copy()->subHours(6),
        ]);

        // Order 2: Sedang Diproses Operator (Prioritas tinggi VVIP)
        $ord2 = Order::create([
            'order_code' => 'CLN-20261023-0102',
            'user_id' => null,
            'customer_name' => 'Bambang Kusumo',
            'customer_phone' => '085611223344',
            'customer_address' => 'Jl. Senopati No. 88, Kebayoran Baru',
            'package_id' => $pkgVvip->id,
            'package_code' => $pkgVvip->code,
            'package_name' => $pkgVvip->name,
            'price_per_kg' => $pkgVvip->price_per_kg,
            'estimated_hours' => $pkgVvip->estimated_hours,
            'priority_level' => $pkgVvip->priority_level,
            'weight' => 6.0,
            'total_price' => 6.0 * $pkgVvip->price_per_kg,
            'driver_id' => $supir->id,
            'driver_item_count' => 22,
            'driver_notes' => 'Titip di pos security',
            'picked_up_at' => $now->copy()->subHours(3),
            'dropped_at' => $now->copy()->subHours(2),
            'operator_id' => $operator->id,
            'operator_item_count' => 22,
            'item_discrepancy' => 0,
            'discrepancy_note' => 'Sesuai dengan catatan supir.',
            'status' => 'proses',
            'estimated_completion_at' => $now->copy()->addHours(10),
            'customer_notes' => 'Mohon parfum lavender.',
            'payment_status' => 'unpaid',
            'created_at' => $now->copy()->subHours(3),
        ]);

        // Order 3: Siap Dikerjakan dengan Discrepancy (Selisih Barang!)
        $ord3 = Order::create([
            'order_code' => 'CLN-20261023-0103',
            'user_id' => null,
            'customer_name' => 'Rina Wijaya',
            'customer_phone' => '087812345678',
            'customer_address' => 'Jl. Menteng Asri No. 14, Jakarta Pusat',
            'package_id' => $pkgVip->id,
            'package_code' => $pkgVip->code,
            'package_name' => $pkgVip->name,
            'price_per_kg' => $pkgVip->price_per_kg,
            'estimated_hours' => $pkgVip->estimated_hours,
            'priority_level' => $pkgVip->priority_level,
            'weight' => 5.2,
            'total_price' => 5.2 * $pkgVip->price_per_kg,
            'driver_id' => $supir->id,
            'driver_item_count' => 15,
            'driver_notes' => 'Laundry tas plastik biru 15 potong',
            'picked_up_at' => $now->copy()->subHours(2),
            'dropped_at' => $now->copy()->subHour(),
            'operator_id' => $operator->id,
            'operator_item_count' => 14, // Discrepancy: driver said 15, operator counted 14!
            'item_discrepancy' => -1,
            'discrepancy_note' => 'Selisih -1 potong: Supir input 15, hasil hitung ulang Operator 14 potong.',
            'status' => 'siap_dikerjakan',
            'estimated_completion_at' => $now->copy()->addHours(23),
            'customer_notes' => 'Ada 1 celana jeans hitam.',
            'payment_status' => 'unpaid',
            'created_at' => $now->copy()->subHours(2),
        ]);

        // Order 4: Tertunda (Pending akibat Penundaan Operator) - Prediksi Waktu Dikaji Ulang
        $ord4 = Order::create([
            'order_code' => 'CLN-20261023-0104',
            'user_id' => null,
            'customer_name' => 'Ahmad Fauzi',
            'customer_phone' => '081399887766',
            'customer_address' => 'Jl. Kebon Sirih No. 22',
            'package_id' => $pkgReguler->id,
            'package_code' => $pkgReguler->code,
            'package_name' => $pkgReguler->name,
            'price_per_kg' => $pkgReguler->price_per_kg,
            'estimated_hours' => $pkgReguler->estimated_hours,
            'priority_level' => $pkgReguler->priority_level,
            'weight' => 7.0,
            'total_price' => 7.0 * $pkgReguler->price_per_kg,
            'driver_id' => $supir->id,
            'driver_item_count' => 25,
            'driver_notes' => 'Diambil dari asisten rumah tangga',
            'picked_up_at' => $now->copy()->subHours(10),
            'dropped_at' => $now->copy()->subHours(9),
            'operator_id' => $operator->id,
            'operator_item_count' => 25,
            'item_discrepancy' => 0,
            'status' => 'pending',
            'delay_reason' => 'Perendaman ekstra untuk noda membandel pada jas & sprei + pemadaman listrik berkala.',
            'delay_minutes' => 240, // 4 hours delay
            'delayed_at' => $now->copy()->subHours(2),
            'estimated_completion_at' => $now->copy()->addHours(42), // recalculated!
            'customer_notes' => 'Baju anak jangan pakai pewangi menyengat.',
            'payment_status' => 'paid',
            'created_at' => $now->copy()->subHours(10),
        ]);

        // Order 5: Telah Diambil Driver (Driver input count, on way to drop at laundry)
        $ord5 = Order::create([
            'order_code' => 'CLN-20261023-0105',
            'user_id' => $customer->id,
            'customer_name' => 'Dewi Lestari',
            'customer_phone' => '082123456789',
            'customer_address' => 'Apartemen Grand Indonesia Tower A No. 15B',
            'package_id' => $pkgVvip->id,
            'package_code' => $pkgVvip->code,
            'package_name' => $pkgVvip->name,
            'price_per_kg' => $pkgVvip->price_per_kg,
            'estimated_hours' => $pkgVvip->estimated_hours,
            'priority_level' => $pkgVvip->priority_level,
            'weight' => 3.8,
            'total_price' => 3.8 * $pkgVvip->price_per_kg,
            'driver_id' => $supir->id,
            'driver_item_count' => 12,
            'driver_notes' => 'Barang sudah dimasukkan mobil, sedang otw ke workshop.',
            'picked_up_at' => $now->copy()->subMinutes(30),
            'status' => 'telah_diambil',
            'estimated_completion_at' => $now->copy()->addHours(12),
            'customer_notes' => 'Baju kerja kemeja 5 pcs.',
            'payment_status' => 'unpaid',
            'created_at' => $now->copy()->subHour(),
        ]);

        // Order 6: Menunggu Penjemputan (New Order from Customer)
        $ord6 = Order::create([
            'order_code' => 'CLN-20261023-0106',
            'user_id' => null,
            'customer_name' => 'Dr. Kevin Pratama',
            'customer_phone' => '081255443322',
            'customer_address' => 'Jl. Teuku Umar No. 5, Menteng',
            'package_id' => $pkgPrioritas->id,
            'package_code' => $pkgPrioritas->code,
            'package_name' => $pkgPrioritas->name,
            'price_per_kg' => $pkgPrioritas->price_per_kg,
            'estimated_hours' => $pkgPrioritas->estimated_hours,
            'priority_level' => $pkgPrioritas->priority_level,
            'weight' => 5.0,
            'total_price' => 5.0 * $pkgPrioritas->price_per_kg,
            'status' => 'menunggu_penjemputan',
            'estimated_completion_at' => $now->copy()->addHours(4),
            'customer_notes' => 'Mohon segera dijemput sore ini.',
            'payment_status' => 'unpaid',
            'created_at' => $now->copy()->subMinutes(15),
        ]);

        // Order 7: Telah Diantar (Riwayat / History)
        $ord7 = Order::create([
            'order_code' => 'CLN-20261022-0099',
            'user_id' => $customer->id,
            'customer_name' => 'Dewi Lestari',
            'customer_phone' => '082123456789',
            'customer_address' => 'Apartemen Grand Indonesia Tower A No. 15B',
            'package_id' => $pkgVip->id,
            'package_code' => $pkgVip->code,
            'package_name' => $pkgVip->name,
            'price_per_kg' => $pkgVip->price_per_kg,
            'estimated_hours' => $pkgVip->estimated_hours,
            'priority_level' => $pkgVip->priority_level,
            'weight' => 8.2,
            'total_price' => 8.2 * $pkgVip->price_per_kg,
            'driver_id' => $supir->id,
            'driver_item_count' => 30,
            'driver_notes' => 'Selesai diantar dan diterima langsung oleh Bu Dewi.',
            'picked_up_at' => $now->copy()->subDays(2),
            'dropped_at' => $now->copy()->subDays(2)->addHours(1),
            'operator_id' => $operator->id,
            'operator_item_count' => 30,
            'item_discrepancy' => 0,
            'status' => 'telah_diantar',
            'estimated_completion_at' => $now->copy()->subDay(),
            'actual_completion_at' => $now->copy()->subDay(),
            'delivered_at' => $now->copy()->subHours(20),
            'customer_notes' => 'Linen hotel & bedcover.',
            'payment_status' => 'paid',
            'created_at' => $now->copy()->subDays(2),
        ]);

        // 4. Seed Activity Logs for Monitoring
        ActivityLog::log($owner, 'PRICE_UPDATE', 'Pembaruan Harga Layanan', 'Owner memperbarui harga Paket Prioritas menjadi Rp 25.000/kg');
        ActivityLog::log($customer, 'ORDER_CREATED', 'Pesanan Baru Dibuat', 'Pelanggan Dewi Lestari memesan Paket Prioritas (CLN-20261023-0101)', $ord1);
        ActivityLog::log($supir, 'DRIVER_PICKUP', 'Penjemputan Pakaian', 'Supir Budi Santoso mengambil 18 potong pakaian dari pelanggan (CLN-20261023-0101)', $ord1, ['count' => 18]);
        ActivityLog::log($supir, 'DRIVER_DROP', 'Pakaian Tiba di Workshop', 'Supir meletakkan (drop) pakaian di laundry untuk dikerjakan (CLN-20261023-0101)', $ord1);
        ActivityLog::log($operator, 'OPERATOR_RECOUNT', 'Hitung Ulang Barang', 'Operator Agus Pratama menghitung ulang: 18 potong (Cocok, tidak ada selisih)', $ord1, ['selisih' => 0]);
        ActivityLog::log($operator, 'OPERATOR_FINISH', 'Proses Laundry Selesai', 'Operator menyelesaikan pencucian dan menyalakan notifikasi Siap Diantar (CLN-20261023-0101)', $ord1);
        
        ActivityLog::log($supir, 'DRIVER_PICKUP', 'Penjemputan Pakaian', 'Supir mengambil 15 potong pakaian dari Rina Wijaya (CLN-20261023-0103)', $ord3, ['count' => 15]);
        ActivityLog::log($operator, 'OPERATOR_RECOUNT_DISCREPANCY', 'Peringatan Selisih Barang', 'Operator menghitung 14 potong vs 15 potong supir (Selisih -1 potong!)', $ord3, ['driver' => 15, 'operator' => 14, 'selisih' => -1]);
        
        ActivityLog::log($operator, 'OPERATOR_DELAY', 'Penundaan Proses Laundry', 'Operator melakukan penundaan 4 jam. Alasan: Perendaman noda membandel + pemadaman listrik. Prediksi selesai dihitung ulang.', $ord4, ['minutes' => 240]);
        ActivityLog::log($supir, 'DRIVER_DELIVER', 'Pengantaran Selesai', 'Supir mengantarkan pakaian selesai ke pelanggan Dewi Lestari (CLN-20261022-0099)', $ord7);
    }
}
