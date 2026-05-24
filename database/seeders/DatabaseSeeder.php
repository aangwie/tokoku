<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin
        User::updateOrCreate(
            ['email' => 'wirawan.aang5@gmail.com'],
            [
                'name' => 'Admin Toko',
                'password' => Hash::make('A4n6w!r4w4n'), // Pastikan untuk mengganti dengan password yang kuat
                'role' => 'admin',
            ]
        );

        // 2. Buat User Customer
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Budi Pelanggan',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 3. Buat Kategori
        $kategoriElektronik = Category::updateOrCreate(
            ['slug' => 'elektronik'],
            ['name' => 'Elektronik']
        );

        $kategoriPakaian = Category::updateOrCreate(
            ['slug' => 'pakaian-pria'],
            ['name' => 'Pakaian Pria']
        );

        $kategoriBuku = Category::updateOrCreate(
            ['slug' => 'buku-dan-alat-tulis'],
            ['name' => 'Buku & Alat Tulis']
        );

        // 4. Buat Produk
        Product::updateOrCreate(
            ['slug' => 'smartphone-super-x'],
            [
                'category_id' => $kategoriElektronik->id,
                'name' => 'Smartphone Super X',
                'description' => 'Smartphone berspesifikasi tinggi dengan layar OLED 6.7 inci, kamera 108MP, dan baterai tahan lama 5000mAh.',
                'price' => 3500000.00,
                'weight' => 250, // gram
                'stock' => 15,
                'image' => null,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'mouse-gaming-wireless'],
            [
                'category_id' => $kategoriElektronik->id,
                'name' => 'Mouse Gaming Wireless',
                'description' => 'Mouse gaming nirkabel ultra-cepat dengan sensor 16000 DPI, pencahayaan RGB, dan daya tahan baterai hingga 80 jam.',
                'price' => 450000.00,
                'weight' => 150, // gram
                'stock' => 30,
                'image' => null,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'kemeja-flanel-premium'],
            [
                'category_id' => $kategoriPakaian->id,
                'name' => 'Kemeja Flanel Premium',
                'description' => 'Kemeja flanel lengan panjang berbahan katun lembut premium. Sangat nyaman untuk gaya kasual sehari-hari.',
                'price' => 189000.00,
                'weight' => 400, // gram
                'stock' => 50,
                'image' => null,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'jaket-bomber-anti-air'],
            [
                'category_id' => $kategoriPakaian->id,
                'name' => 'Jaket Bomber Anti Air',
                'description' => 'Jaket bomber dengan bahan pelindung anti air (waterproof) dan lapisan dalam yang hangat. Cocok untuk berkendara di malam hari.',
                'price' => 299000.00,
                'weight' => 800, // gram
                'stock' => 20,
                'image' => null,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'buku-panduan-laravel-12'],
            [
                'category_id' => $kategoriBuku->id,
                'name' => 'Buku Panduan Laravel 12',
                'description' => 'Buku panduan lengkap membangun aplikasi web modern menggunakan Laravel 12, Tailwind CSS, dan Livewire untuk pemula hingga mahir.',
                'price' => 125000.00,
                'weight' => 550, // gram
                'stock' => 25,
                'image' => null,
            ]
        );

        // 5. Buat Kupon Diskon
        Coupon::updateOrCreate(
            ['code' => 'DISKON20'],
            [
                'type' => 'percentage',
                'value' => 20.00, // 20%
                'min_order' => 50000.00,
                'max_uses' => 100,
                'used_count' => 0,
                'expires_at' => now()->addDays(30),
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'POTONG50K'],
            [
                'type' => 'fixed',
                'value' => 50000.00, // Rp 50.000
                'min_order' => 150000.00,
                'max_uses' => 50,
                'used_count' => 0,
                'expires_at' => now()->addDays(15),
            ]
        );

        // 6. Pengaturan Toko (Settings)
        Setting::set('store_name', 'Toko BillNesia');
        Setting::set('store_logo', 'images/store_logo.jpg');
        Setting::set('payment_method', 'paymentgateway');
    }
}
