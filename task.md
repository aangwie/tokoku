# Daftar Tugas (Task List) - Tahap 2 s/d Tahap 4

## Tahap 2: Manajemen Admin & Katalog (Optimasi Controller)
- [ ] Pembuatan Middleware Admin (`IsAdmin`) & Registrasi di `bootstrap/app.php`
- [ ] Pembuatan Image Compression Service (`App\Services\ImageService` menggunakan GD)
- [ ] Pembuatan Controller Admin (Category, Product, Coupon, Order)
  - [ ] `Admin\CategoryController`
  - [ ] `Admin\ProductController`
  - [ ] `Admin\CouponController`
  - [ ] `Admin\OrderController`
- [ ] Pembuatan View Admin (Blade + Tailwind + SweetAlert2)
  - [ ] Layout & Navigasi Admin
  - [ ] CRUD Kategori (Halaman Indeks, Tambah, Edit)
  - [ ] CRUD Produk (Halaman Indeks, Tambah dengan Upload Gambar, Edit)
  - [ ] CRUD Kupon (Halaman Indeks, Tambah, Edit)
  - [ ] Manajemen Pesanan (Halaman Indeks, Detail, Input Resi/Status)
- [ ] Pembuatan Halaman Depan & Katalog Pelanggan (Anti N+1 Eager Loading)
  - [ ] `HomeController`
  - [ ] View Katalog Utama & Detail Produk

## Tahap 3: Transaksi & Integrasi Gateway
- [x] Pembuatan Keranjang Belanja & Logika Kupon
  - [x] `CartController` (Session-based Cart, Tambah/Hapus/Update, Validasi Kupon)
  - [x] View Keranjang & Checkout
- [x] Integrasi Payment Gateway (Midtrans Snap & Xendit Invoice)
  - [x] Konfigurasi Kredensial di `.env` & `config/services.php`
  - [x] `App\Services\MidtransService`
  - [x] `App\Services\XenditService`
  - [x] `WebhookController` untuk menangani callback status otomatis
- [x] Halaman Riwayat & Pelacakan Pesanan Pelanggan (Order Tracking)

## Tahap 4: Integrasi Notifikasi (Baileys & Telegram)
- [x] Pembuatan Server WhatsApp Gateway Mandiri (`whatsapp-gateway/` menggunakan Node.js & Baileys)
  - [x] Inisialisasi Project Node.js, `index.js`, `package.json`
  - [x] Endpoint `/send-message` dan penanganan autentikasi QR Code via terminal/web
- [x] Integrasi Telegram Bot (HTTP Client) di Laravel
- [x] Notification Service Laravel (`App\Services\NotificationService`)
- [x] Event & Listener
  - [x] Event `OrderStatusChanged` & Listener `SendOrderStatusNotification`
  - [x] Integrasi Observer/Event pada model `Order` untuk memicu notifikasi

## Tahap 5: Pengaturan Toko & Dashboard Analitik Penjualan
- [x] Copy logo asset dan pembuatan migration serta model Setting
- [x] Pembuatan Controller Admin Setting (`SettingController`) untuk edit/update pengaturan dengan kompresi gambar otomatis
- [x] Pembuatan Halaman Pengaturan Toko (`resources/views/admin/settings/edit.blade.php`)
- [x] Pembuatan Controller Admin Dashboard (`DashboardController`) dengan visualisasi statistik & anti N+1 query
- [x] Pembuatan Halaman Dashboard Admin (`resources/views/admin/dashboard.blade.php`)
- [x] Update routing di `routes/web.php` dan link navigasi di layout
- [x] Update view layout customer dan komponen logo agar memuat logo & nama toko secara dinamis
- [x] Pengujian & Verifikasi Fitur dengan unit/feature testing dan refresh seeding database
