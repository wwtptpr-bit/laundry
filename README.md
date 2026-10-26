
# CROWN & LUCKY Laundry - Sistem Web Laravel Multi-Role

Sistem aplikasi web laundry profesional berbasis **Laravel 11 & PHP 8.3** dengan tampilan antarmuka (UI) dashboard publik yang identik dengan tema desain `CROWN & LUCKY Laundry` (warna hijau emerald/primary, tipografi Playfair Display & Roboto, efek riak air, dan kartu modern).

Sistem dilengkapi dengan sistem autentikasi dan otorisasi untuk **5 Role Pengguna**:

---

## 5 Role dan Fitur Sistem

### 1. 👑 Owner (Pemilik)
- **Ubah Harga Paket Publik**: Owner dapat mengubah harga per kg, estimasi waktu, fitur layanan, dan badge paket di dashboard publik (`/owner/prices`). Perubahan harga langsung tayang seketika di halaman utama / publik.
- **Monitoring 4 Role Lainnya**: Memantau seluruh aktivitas, audit log, dan riwayat kegiatan dari Admin, Supir, Operator, dan Customer secara realtime (`/owner/monitoring`).
- **Dashboard Ringkasan & KPI**: Memantau total omset/pendapatan, jumlah cucian aktif, peringatan selisih barang, dan seluruh data transaksi.

### 2. 🛡️ Admin (Monitoring Read-Only)
- **Tampilan Mirror Owner**: Menampilkan seluruh data statistik, pesanan, dan monitoring seperti yang dilihat oleh Owner.
- **Read-Only / Tanpa Hak Ubah**: Admin hanya bertugas memantau dan tidak dapat mengubah harga maupun data operasional.

### 3. 🚚 Supir (Driver Antar - Jemput)
- **Input Jumlah Total Cucian**: Saat mengambil cucian dari pelanggan, supir menginput jumlah potong pakaian (`driver_item_count`), berat kg, dan catatan. Data ini langsung terlihat oleh Admin, Owner, Operator, dan Customer.
- **Ubah Status Drop**: Mengubah status dari `telah_diambil` menjadi `telah_drop` setibanya di tempat laundry (workshop).
- **Notifikasi Siap Diantar**: Ketika operator menyelesaikan pencucian dan menandai `siap_diantar`, notifikasi alarm/antrean pengantaran langsung muncul di dashboard supir.
- **Ubah Status Telah Diantar**: Setelah pakaian sampai di tangan pelanggan, supir mengubah status menjadi `telah_diantar`, yang langsung tampil selesai pada akun Owner, Admin, dan Customer.

### 4. ⚙️ Operator Laundry
- **Antrian Prioritas Otomatis**: Antrian otomatis diurutkan berdasarkan paket layanan pelanggan:
  $$\text{Prioritas} > \text{VVIP} > \text{VIP} > \text{Reguler}$$
- **Muncul Saat Jemput & Drop**: Antrean muncul saat supir mengambil barang, dan menjadi `siap_dikerjakan` saat supir melakukan drop.
- **Hitung Ulang Barang (QC)**: Operator menginput ulang jumlah pakaian tanpa menimpa/mengubah data input supir. Sistem otomatis membandingkan:
  - Jika cocok: Ditandai hijau (0 selisih).
  - Jika berbeda: Ditandai merah **⚠️ Peringatan Selisih Barang** (misal: Supir 15 pcs, Operator 14 pcs) yang diteruskan ke Owner & Admin.
- **Penundaan Proses (Pending)**: Operator dapat menunda proses dengan alasan tertentu. Sistem otomatis mengkaji ulang (memperpanjang) prediksi waktu selesai dan memberitahu pelanggan.
- **Proses Selesai / Siap Diantar**: Meneruskan info ke Owner, Admin, Customer, serta memicu notifikasi pengantaran pada Supir.

### 5. 👤 Customer (Pelanggan)
- **Pilihan 4 Paket Layanan**:
  1. **Reguler**: Antrean standar ekonomis (Selesai 2-3 hari).
  2. **VIP**: Diprioritaskan di atas Reguler (Selesai 24 jam).
  3. **VVIP**: Diprioritaskan di atas VIP & Reguler (Selesai 12 jam).
  4. **Prioritas**: Dikerjakan paling awal / Top Priority (Selesai 4 jam kilat).
- **Prediksi Kapan Selesai**: Melihat estimasi jam/tanggal selesai secara realtime. Jika terjadi penundaan oleh operator, prediksi otomatis dikaji ulang beserta alasannya.
- **Menu History (Riwayat)**: Melihat daftar seluruh pesanan yang telah selesai diantar lengkap dengan rincian resi dan bukti serah terima.

---

## Akun Login Default (5 Role)

Semua akun demo menggunakan kata sandi: `password`

| No | Role | Email | Password | Hak Akses Utama |
|---|---|---|---|---|
| 1 | **Owner** | `owner@crownlucky.com` | `password` | Ubah harga paket publik, monitor 4 role, KPI omset |
| 2 | **Admin** | `admin@crownlucky.com` | `password` | Monitoring seluruh data (Read-Only) |
| 3 | **Supir** | `supir@crownlucky.com` | `password` | Input jumlah pakaian jemput, drop laundry, antar pesanan |
| 4 | **Operator** | `operator@crownlucky.com` | `password` | Antrian prioritas, hitung selisih, tunda, proses selesai |
| 5 | **Customer** | `customer@gmail.com` | `password` | Pilih 4 paket, pantau prediksi selesai, menu history |

> **Catatan Kemudahan:** Pada halaman login (`/login`) dan topbar dashboard, tersedia fitur **Quick-Switch 1-Click** untuk langsung berganti antar 5 role tanpa perlu mengetik ulang email dan password!

---

## Cara Menjalankan Aplikasi

Aplikasi dapat dijalankan melalui Laragon (Apache) atau melalui command line:

```bash
# Masuk ke direktori projek
cd C:\laragon\www\laundry

# Menjalankan server Laravel lokal
php artisan serve
```

Buka peramban (browser) di:
- **Landing Page Publik**: `http://localhost:8000` atau `http://laundry.test`
- **Halaman Login 5 Role**: `http://localhost:8000/login`
- **Pelacakan Resi Publik**: `http://localhost:8000/track` (Contoh kode: `CLN-20261023-0101`)
