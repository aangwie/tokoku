# 🛒 TokoKu - E-Commerce Platform

TokoKu adalah platform e-commerce modern yang dibangun dengan Laravel 11, menyediakan fitur lengkap untuk mengelola toko online dengan sistem pembayaran terintegrasi dan notifikasi WhatsApp otomatis.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## ✨ Fitur Utama

### 🎯 Untuk Admin
- **Dashboard Analytics** - Statistik penjualan, revenue, dan order real-time
- **Manajemen Produk** - CRUD produk dengan upload gambar, kategori, dan stok
- **Manajemen Kategori** - Organisasi produk dengan kategori
- **Manajemen Kupon** - Buat dan kelola kupon diskon (persentase/nominal)
- **Manajemen Pesanan** - Tracking status pesanan dari pending hingga selesai
- **Pengaturan Toko** - Konfigurasi nama toko, logo, kontak, dan informasi lainnya
- **Pengaturan Profil Admin** - Update email dan password admin
- **Halaman Statis** - Kelola halaman Terms & Conditions dan Refund Policy
- **System Update** - Update aplikasi langsung dari GitHub

### 🛍️ Untuk Customer
- **Katalog Produk** - Browse produk dengan filter kategori dan pencarian
- **Keranjang Belanja** - Tambah/hapus produk, update quantity
- **Checkout** - Proses pembelian dengan pilihan alamat pengiriman
- **Pembayaran Midtrans** - Integrasi payment gateway (Credit Card, E-Wallet, Bank Transfer)
- **Manajemen Alamat** - Kelola multiple alamat dengan data wilayah Indonesia lengkap (Provinsi, Kota, Kecamatan, Kelurahan)
- **Manajemen Rekening Bank** - Simpan rekening untuk refund
- **Riwayat Pesanan** - Lihat status dan detail pesanan
- **Invoice Digital** - Download invoice PDF
- **Notifikasi WhatsApp** - Notifikasi otomatis untuk status pesanan

### 🔧 Fitur Teknis
- **Responsive Design** - Mobile-friendly dengan Tailwind CSS
- **Dark Mode** - Tema gelap untuk kenyamanan mata
- **Image Optimization** - Kompresi otomatis gambar produk
- **API Wilayah Indonesia** - Integrasi dengan API emsifa.com untuk data wilayah
- **FlyonUI Select** - Dropdown searchable untuk kemudahan memilih wilayah
- **SweetAlert2** - Notifikasi interaktif yang menarik
- **Alpine.js** - Interaktivitas frontend yang ringan

---

## 📋 System Requirements

Sebelum menginstall aplikasi, pastikan sistem Anda memenuhi requirement berikut:

### Minimum Requirements
- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **MySQL** >= 8.0 atau **MariaDB** >= 10.3
- **Git** (untuk clone repository)

### PHP Extensions Required
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- PDO_MySQL
- Tokenizer
- XML
- GD atau Imagick (untuk image processing)

### Recommended
- **XAMPP** 8.2+ (sudah include PHP, MySQL, Apache)
- **Laragon** (alternatif untuk Windows)
- **RAM** minimal 2GB
- **Storage** minimal 500MB free space

---

## 🚀 Instalasi

### 1. Clone Repository

Clone repository dari GitHub:

```bash
git clone https://github.com/aangwie/tokoku.git
cd tokoku
```

### 2. Install Dependencies

Install PHP dependencies menggunakan Composer:

```bash
composer install
```

Install Node.js dependencies:

```bash
npm install
```

### 3. Environment Setup

Copy file `.env.example` menjadi `.env`:

```bash
# Windows (CMD)
copy .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env

# Linux/Mac
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Database Configuration

Buat database baru di MySQL:

```sql
CREATE DATABASE tokoku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tokoku
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations & Seeders

Jalankan migration untuk membuat tabel database:

```bash
php artisan migrate
```

Jalankan seeder untuk data awal (admin, kategori, produk sample):

```bash
php artisan db:seed
```

### 6. Storage Link

Buat symbolic link untuk storage (agar gambar bisa diakses):

```bash
php artisan storage:link
```

### 7. Build Assets

Compile CSS dan JavaScript assets:

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Konfigurasi Midtrans (Payment Gateway)

Edit file `.env` dan tambahkan kredensial Midtrans:

```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Cara mendapatkan Midtrans Key:**
1. Daftar di [https://dashboard.midtrans.com/register](https://dashboard.midtrans.com/register)
2. Login ke dashboard
3. Pilih environment (Sandbox untuk testing)
4. Copy Server Key dan Client Key dari Settings > Access Keys

### 9. Konfigurasi WhatsApp Gateway (Opsional)

Jika ingin mengaktifkan notifikasi WhatsApp, edit file `.env`:

```env
WHATSAPP_GATEWAY_URL=http://localhost:3000
WHATSAPP_GATEWAY_TOKEN=your_token
```

Setup WhatsApp Gateway:

```bash
cd whatsapp-gateway
npm install
node index.js
```

Scan QR code untuk menghubungkan WhatsApp.

---

## 🎮 Cara Mengakses Aplikasi

### Menjalankan Development Server

Jalankan Laravel development server:

```bash
php artisan serve
```

Atau jika menggunakan XAMPP, pastikan Apache dan MySQL sudah running, lalu akses:

```
http://localhost/tokoku/public
```

Untuk built-in PHP server:

```bash
php -S localhost:8000 -t public
```

### Akses Admin Panel

**URL:** `http://localhost:8000/admin/login`

**Default Admin Credentials:**
- Email: `admin@tokoku.com`
- Password: `password`

**Fitur Admin:**
- Dashboard: `/admin/dashboard`
- Produk: `/admin/products`
- Kategori: `/admin/categories`
- Kupon: `/admin/coupons`
- Pesanan: `/admin/orders`
- Pengaturan: `/admin/settings`
- Profil: `/admin/settings/profile`

### Akses Customer/User

**URL:** `http://localhost:8000`

**Register Customer Baru:**
1. Klik "Register" di halaman utama
2. Isi form registrasi
3. Login dengan email dan password yang didaftarkan

**Fitur Customer:**
- Home/Katalog: `/`
- Detail Produk: `/products/{id}`
- Keranjang: `/cart`
- Checkout: `/checkout`
- Pesanan Saya: `/orders`
- Pengaturan Akun: `/settings`

---

## 🛠️ Tech Stack

### Backend
- **Laravel 11** - PHP Framework
- **MySQL** - Database
- **Midtrans** - Payment Gateway
- **WhatsApp Web.js** - WhatsApp Integration

### Frontend
- **Blade Templates** - Laravel Templating Engine
- **Tailwind CSS** - Utility-first CSS Framework
- **Alpine.js** - Lightweight JavaScript Framework
- **FlyonUI** - UI Component Library
- **SweetAlert2** - Beautiful Alert Dialogs
- **Vite** - Frontend Build Tool

### Libraries & Tools
- **Intervention Image** - Image Processing
- **Laravel Breeze** - Authentication Scaffolding
- **Spatie Laravel Permission** - Role & Permission Management

---

## 📁 Struktur Project

```
tokoku/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Api/            # API controllers
│   │   └── ...
│   ├── Models/             # Eloquent models
│   └── Services/           # Business logic services
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── public/
│   ├── images/             # Public images
│   └── storage/            # Symlinked storage
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # CSS files
│   └── js/                 # JavaScript files
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
├── storage/
│   └── app/public/         # Uploaded files
└── whatsapp-gateway/       # WhatsApp service
```

---

## 🔐 Security

- Ubah `APP_KEY` di file `.env` (sudah otomatis saat `php artisan key:generate`)
- Ubah password admin default setelah instalasi
- Jangan commit file `.env` ke repository
- Gunakan HTTPS di production
- Set `APP_DEBUG=false` di production
- Backup database secara berkala

---

## 🐛 Troubleshooting

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "The stream or file could not be opened"
```bash
# Windows
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T

# Linux/Mac
chmod -R 775 storage bootstrap/cache
```

### Error: "SQLSTATE[HY000] [1045] Access denied"
- Periksa konfigurasi database di file `.env`
- Pastikan MySQL service sudah running
- Pastikan username dan password benar

### Assets tidak muncul
```bash
npm run build
php artisan storage:link
```

### Gambar produk tidak muncul
```bash
php artisan storage:link
```

---

## 📝 Update Aplikasi

Untuk update aplikasi ke versi terbaru:

1. **Via Admin Panel:**
   - Login sebagai admin
   - Buka menu "System Update"
   - Klik "Check for Updates"
   - Klik "Update Now" jika ada update

2. **Via Command Line:**
   ```bash
   git pull origin main
   composer install
   npm install
   npm run build
   php artisan migrate
   php artisan optimize:clear
   ```

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Developer

Developed with ❤️ by [aangwie](https://github.com/aangwie)

---

## 📞 Support

Jika ada pertanyaan atau masalah, silakan buat issue di [GitHub Issues](https://github.com/aangwie/tokoku/issues)

---

**Happy Coding! 🚀**