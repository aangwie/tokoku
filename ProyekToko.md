# Product Requirement Document (PRD) - Toko Online Individu (V2)

## 1. Project Overview
* **Nama Projek:** Toko Online Individu (Single Vendor) dengan Multi-Gateway & Notifikasi Otomatis
* **Deskripsi:** Aplikasi e-commerce skala kecil/menengah khusus untuk satu penjual. Dilengkapi sistem pembayaran otomatis, manajemen diskon, serta notifikasi instan ke WhatsApp dan Telegram.
* **Tech Stack:**
  * **Backend:** PHP 8.2 & Laravel 12
  * **Database:** MySQL
  * **Frontend:** Tailwind CSS / Bootstrap (Utamakan yang ringan, responsif cross-browser, tanpa library JS berat).

---

## 2. User Personas & Roles
1. **Admin (Pemilik Toko):** Mengelola produk, kategori, diskon/kupon, melihat transaksi, memantau server WhatsApp (Baileys), dan mengubah status pesanan manual jika diperlukan.
2. **Pelanggan (Customer):** Menjelajahi toko, klaim diskon, checkout dengan payment gateway otomatis, serta menerima notifikasi langsung ke WhatsApp/Telegram.

---

## 3. Core Features (Fitur Utama & Spesifikasi Teknis)

### A. Fitur Pelanggan & Frontend Performance
1. **Katalog & Detail Produk:** Tampilan responsif di semua browser modern (Mobile-first design). Aset gambar harus di-compress otomatis saat upload agar web ringan diakses.
2. **Keranjang & Sistem Diskon:** 
   * Input kode kupon/diskon di halaman keranjang belanja.
   * Potongan harga bisa berupa nominal flat (Rp) atau persentase (%).
3. **Checkout & Multi-Payment Gateway:**
   * Pengisian alamat dan kalkulasi ongkir.
   * Integrasi **Midtrans** (Snap API) dan **Xendit** (Invoice API) sebagai opsi pembayaran otomatis.
4. **Order Tracking:** Riwayat pesanan dengan status yang ter-update otomatis via webhook dari payment gateway.

### B. Fitur Admin & Otomatisasi Backend
1. **Manajemen Produk & Kategori:** CRUD standar dilengkapi input berat (gram) dan manajemen stok.
2. **Manajemen Diskon/Kupon:** CRUD kode kupon, masa berlaku, minimal pembelian, dan kuota pemakaian.
3. **Sistem Notifikasi Dual-Channel:**
   * **WhatsApp (Baileys):** Memanfaatkan library Baileys (Node.js) sebagai WhatsApp Gateway mandiri. Laravel mengirim request API ke service Baileys untuk mengirim pesan ke nomor customer saat checkout, pembayaran sukses, dan input resi.
   * **Telegram Bot:** Mengirimkan notifikasi ke grup/channel Telegram Admin setiap kali ada pesanan baru masuk atau pembayaran berhasil.

---

## 4. Architectural Rules & Database (WAJIB UNTUK AI)

### A. Backend Performance Optimization (Anti N+1 Query)
* **DILARANG** melakukan loop query mentah atau memanggil relasi langsung di dalam blade views (misal: `@foreach($products as $p) {{ $p->category->name }} @endforeach` tanpa optimasi).
* **WAJIB** menggunakan **Eager Loading (`with()`)** di dalam Controller untuk memuat data relasi dalam jumlah banyak sebelum dikirim ke view.
  * *Contoh:* `Product::with('category')->get();`

### B. Database Schema Expansion
* `users` (id, name, email, password, role)
* `categories` (id, name, slug)
* `products` (id, category_id, name, slug, description, price, weight, stock, image)
* `coupons` (id, code, type[percentage/fixed], value, min_order, max_uses, used_count, expires_at)
* `orders` (id, user_id, coupon_id, order_number, subtotal, discount_amount, total_price, shipping_cost, status, payment_gateway[midtrans/xendit], payment_reference, shipping_address, tracking_number)
* `order_items` (id, order_id, product_id, quantity, price)

---

## 5. Tahapan Pengembangan (Development Steps untuk AI)

Gunakan urutan langkah ini untuk menginstruksikan AI agar kode tidak tumpang tindih:

### Tahap 1: Fondasi & Basis Data
1. **Step 1:** Setup awal projek Laravel 12 (PHP 8.2), konfigurasi `.env`, install Auth starter kit (Laravel Breeze/Livewire) dengan aset CSS yang ringan dan responsif.
2. **Step 2:** Pembuatan Migration, Model, dan Relationship (User, Category, Product, Coupon, Order, OrderItem). 

### Tahap 2: Manajemen Admin & Katalog (Optimasi Controller)
3. **Step 3:** Pembuatan Dashboard Admin untuk CRUD Kategori, Produk, dan Kupon Diskon.
4. **Step 4:** Pembuatan Katalog Produk untuk sisi user. **Pastikan controller menggunakan `with()` untuk eager loading relasi** dan layouting responsif di browser mobile maupun desktop.

### Tahap 3: Transaksi & Integrasi Gateway
5. **Step 5:** Pembuatan logika Keranjang Belanja, validasi kode kupon diskon, dan perhitungan harga akhir.
6. **Step 6:** Integrasi Pembayaran Otomatis. Buat service Class khusus untuk **Midtrans API** dan **Xendit API**, serta setup route Webhook untuk menangani perubahan status pesanan otomatis saat dibayar.

### Tahap 4: Integrasi Notifikasi (Baileys & Telegram)
7. **Step 7:** Pembuatan Notification Service di Laravel.
   * Setup endpoint/client untuk menembak ke server **Baileys Node.js** untuk WhatsApp.
   * Setup integrasi **Telegram Bot API** (menggunakan HTTP Client bawaan Laravel).
8. **Step 8:** Pasang Event / Listener di Laravel agar notifikasi terpicu otomatis setiap kali status di tabel `orders` berubah.

### Tahap 5: Pengaturan Toko & Dashboard Analitik Penjualan
9. **Step 9:** Pembuatan basis data pengaturan toko (Settings) dengan relasi key-value, model `Setting` yang mendukung in-memory caching untuk mengurangi query ke database.
10. **Step 10:** Pembuatan halaman Pengaturan Toko (Store Settings) di dashboard admin untuk mengunggah logo toko ("BN" logo) dan memperbarui nama toko, lengkap dengan kompresi gambar otomatis ke format WebP via `ImageService`.
11. **Step 11:** Pembuatan Dashboard Analitik Penjualan premium dengan visualisasi pendapatan, pesanan, total produk/kategori, daftar produk terlaris (eager loaded), pesanan terbaru, dan tren penjualan bulanan (optimasi query anti N+1).