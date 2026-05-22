# Daftar Tugas (Task List) - Tahap 2 s/d Tahap 6

## Tahap 2: Manajemen Admin & Katalog (Optimasi Controller)
- [x] Pembuatan Middleware Admin (`IsAdmin`) & Registrasi di `bootstrap/app.php`
- [x] Pembuatan Image Compression Service (`App\Services\ImageService` menggunakan GD)
- [x] Pembuatan Controller Admin (Category, Product, Coupon, Order)
  - [x] `Admin\CategoryController`
  - [x] `Admin\ProductController`
  - [x] `Admin\CouponController`
  - [x] `Admin\OrderController`
- [x] Pembuatan View Admin (Blade + Tailwind + SweetAlert2)
  - [x] Layout & Navigasi Admin
  - [x] CRUD Kategori (Halaman Indeks, Tambah, Edit)
  - [x] CRUD Produk (Halaman Indeks, Tambah dengan Upload Gambar, Edit)
  - [x] CRUD Kupon (Halaman Indeks, Tambah, Edit)
  - [x] Manajemen Pesanan (Halaman Indeks, Detail, Input Resi/Status)
- [x] Pembuatan Halaman Depan & Katalog Pelanggan (Anti N+1 Eager Loading)
  - [x] `HomeController`
  - [x] View Katalog Utama & Detail Produk

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
  - [x] Panel konfigurasi Bank Transfer (daftar rekening bank dinamis)
  - [x] Panel konfigurasi Payment Gateway (Midtrans & Xendit API keys)
- [x] Pembuatan Controller Admin Dashboard (`DashboardController`) dengan visualisasi statistik & anti N+1 query
- [x] Pembuatan Halaman Dashboard Admin (`resources/views/admin/dashboard.blade.php`)
- [x] Update routing di `routes/web.php` dan link navigasi di layout
- [x] Update view layout customer dan komponen logo agar memuat logo & nama toko secara dinamis
- [x] Background gradasi #91ebff → #ffffff di semua layout (app, customer, guest)
- [x] Dynamic title menggunakan `Setting::get('store_name')`
- [x] Pengujian & Verifikasi Fitur dengan unit/feature testing dan refresh seeding database

## Tahap 6: Pengaturan Pelanggan (Alamat & Rekening)
- [x] Pembuatan migration `customer_addresses` dan `customer_bank_accounts`
- [x] Pembuatan model `CustomerAddress` dan `CustomerBankAccount`
- [x] Penambahan relasi `customerAddresses()` dan `customerBankAccounts()` di model `User`
- [x] Pembuatan `CustomerSettingController` (CRUD alamat & rekening bank)
- [x] Pembuatan halaman pengaturan pelanggan (`resources/views/customer/settings.blade.php`)
- [x] Update sidebar dengan menu "Pengaturan" untuk customer (desktop & mobile)
- [x] Update checkout view dengan selector alamat tersimpan (`addressSelector()` Alpine.js)
- [x] Pembuatan Feature Test `CustomerSettingTest.php` (10 tests, 28 assertions)
- [x] Build frontend assets (`npm run build`)
- [x] Semua 71 test passed (210 assertions)
- [x] Perbaikan layout halaman /orders (riwayat belanja) & detail pesanan agar menggunakan `app-layout` dengan sidebar
- [x] Optimasi sidebar menu pelanggan dan persistensi state `sidebarOpen` di localStorage agar tidak tersembunyi saat navigasi
- [x] Pembuatan fitur edit profil pembeli (nama, nomor handphone, password baru) langsung di halaman `/settings` beserta dengan validasinya
- [x] Penambahan 4 Feature Test baru untuk validasi & update profil pembeli


