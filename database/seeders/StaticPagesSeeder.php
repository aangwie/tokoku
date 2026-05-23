<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class StaticPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Terms and Conditions Content
        $termsContent = <<<'HTML'
<h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 1.5rem; color: #1f2937;">Syarat & Ketentuan</h2>

<p style="margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">Selamat datang di toko kami. Dengan mengakses dan menggunakan website ini, Anda setuju untuk terikat dengan syarat dan ketentuan berikut:</p>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">1. Penggunaan Website</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Website ini hanya boleh digunakan untuk tujuan yang sah dan sesuai dengan hukum yang berlaku</li>
    <li>Anda tidak diperkenankan menggunakan website ini untuk tujuan yang melanggar hukum atau merugikan pihak lain</li>
    <li>Kami berhak untuk menangguhkan atau menghentikan akses Anda jika melanggar syarat dan ketentuan ini</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">2. Akun Pengguna</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Anda bertanggung jawab untuk menjaga kerahasiaan informasi akun Anda</li>
    <li>Anda bertanggung jawab atas semua aktivitas yang terjadi di bawah akun Anda</li>
    <li>Segera beritahu kami jika terjadi penggunaan akun yang tidak sah</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">3. Pemesanan dan Pembayaran</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Semua pesanan tunduk pada ketersediaan produk</li>
    <li>Kami berhak menolak atau membatalkan pesanan karena alasan tertentu</li>
    <li>Harga produk dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya</li>
    <li>Pembayaran harus dilakukan sesuai dengan metode yang tersedia</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">4. Pengiriman</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Waktu pengiriman yang disebutkan adalah estimasi dan dapat berubah</li>
    <li>Kami tidak bertanggung jawab atas keterlambatan pengiriman yang disebabkan oleh pihak ketiga</li>
    <li>Risiko kehilangan atau kerusakan barang berpindah kepada Anda setelah pengiriman</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">5. Hak Kekayaan Intelektual</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Semua konten di website ini dilindungi oleh hak cipta dan hak kekayaan intelektual lainnya</li>
    <li>Anda tidak diperkenankan menggunakan, menyalin, atau mendistribusikan konten tanpa izin tertulis</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">6. Batasan Tanggung Jawab</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Kami tidak bertanggung jawab atas kerugian langsung, tidak langsung, atau konsekuensial</li>
    <li>Kami tidak menjamin bahwa website akan selalu tersedia atau bebas dari kesalahan</li>
    <li>Kami tidak bertanggung jawab atas konten atau praktik dari website pihak ketiga yang terhubung</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">7. Perubahan Syarat & Ketentuan</h3>
<p style="margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">Kami berhak untuk mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan berlaku segera setelah dipublikasikan di website. Penggunaan website setelah perubahan berarti Anda menyetujui syarat dan ketentuan yang baru.</p>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">8. Hukum yang Berlaku</h3>
<p style="margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">Syarat dan ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum yang berlaku di Indonesia. Setiap perselisihan akan diselesaikan melalui pengadilan yang berwenang.</p>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">9. Kontak</h3>
<p style="margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">Jika Anda memiliki pertanyaan tentang syarat dan ketentuan ini, silakan hubungi kami melalui informasi kontak yang tersedia di website.</p>

<p style="margin-top: 2rem; font-style: italic; color: #6b7280;">Terakhir diperbarui: {{ date('d F Y') }}</p>
HTML;

        // Refund Policy Content
        $refundContent = <<<'HTML'
<h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 1.5rem; color: #1f2937;">Kebijakan Pengembalian Dana</h2>

<p style="margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">Kami berkomitmen untuk memberikan kepuasan kepada pelanggan kami. Berikut adalah kebijakan pengembalian dana kami:</p>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">1. Ketentuan Umum</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Pengembalian dana hanya berlaku untuk produk yang memenuhi syarat dan ketentuan yang berlaku</li>
    <li>Permintaan pengembalian dana harus diajukan dalam waktu 7 hari setelah produk diterima</li>
    <li>Produk harus dalam kondisi asli, tidak digunakan, dan dalam kemasan asli</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">2. Alasan Pengembalian Dana</h3>
<p style="margin-bottom: 0.5rem; line-height: 1.75; color: #4b5563;">Kami menerima permintaan pengembalian dana untuk alasan berikut:</p>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li><strong>Produk Rusak atau Cacat:</strong> Produk diterima dalam kondisi rusak atau cacat produksi</li>
    <li><strong>Produk Salah:</strong> Produk yang diterima tidak sesuai dengan pesanan</li>
    <li><strong>Produk Tidak Lengkap:</strong> Produk diterima tidak lengkap atau ada bagian yang hilang</li>
    <li><strong>Tidak Sesuai Deskripsi:</strong> Produk tidak sesuai dengan deskripsi yang tertera di website</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">3. Produk yang Tidak Dapat Dikembalikan</h3>
<p style="margin-bottom: 0.5rem; line-height: 1.75; color: #4b5563;">Berikut adalah produk yang tidak dapat dikembalikan:</p>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Produk yang sudah digunakan atau tidak dalam kondisi asli</li>
    <li>Produk tanpa kemasan asli atau label yang rusak</li>
    <li>Produk yang dibeli saat promo atau diskon khusus (kecuali ada cacat produksi)</li>
    <li>Produk custom atau made-to-order</li>
    <li>Produk digital atau downloadable</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">4. Prosedur Pengembalian Dana</h3>
<p style="margin-bottom: 0.5rem; line-height: 1.75; color: #4b5563;">Untuk mengajukan pengembalian dana, ikuti langkah-langkah berikut:</p>
<ol style="list-style-type: decimal; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li><strong>Hubungi Kami:</strong> Hubungi customer service kami melalui WhatsApp atau email dengan menyertakan nomor pesanan dan alasan pengembalian</li>
    <li><strong>Kirim Bukti:</strong> Kirimkan foto produk yang menunjukkan kerusakan atau ketidaksesuaian</li>
    <li><strong>Tunggu Persetujuan:</strong> Tim kami akan meninjau permintaan Anda dalam waktu 1-3 hari kerja</li>
    <li><strong>Kirim Kembali Produk:</strong> Jika disetujui, kirim kembali produk ke alamat yang kami berikan</li>
    <li><strong>Verifikasi:</strong> Setelah produk diterima dan diverifikasi, kami akan memproses pengembalian dana</li>
</ol>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">5. Waktu Pemrosesan</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Pengembalian dana akan diproses dalam waktu 7-14 hari kerja setelah produk diterima dan diverifikasi</li>
    <li>Dana akan dikembalikan ke metode pembayaran yang sama dengan yang digunakan saat pembelian</li>
    <li>Untuk pembayaran transfer bank, dana akan ditransfer ke rekening yang Anda daftarkan</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">6. Biaya Pengiriman</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Jika pengembalian disebabkan oleh kesalahan kami (produk rusak, salah kirim, dll), kami akan menanggung biaya pengiriman</li>
    <li>Jika pengembalian karena alasan pribadi, biaya pengiriman ditanggung oleh pembeli</li>
    <li>Biaya pengiriman awal tidak dapat dikembalikan</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">7. Penukaran Produk</h3>
<p style="margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">Jika Anda ingin menukar produk dengan produk lain, silakan hubungi customer service kami. Penukaran hanya dapat dilakukan jika produk pengganti tersedia dan memenuhi syarat yang sama dengan pengembalian dana.</p>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">8. Pembatalan Pesanan</h3>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>Pembatalan pesanan dapat dilakukan sebelum pesanan diproses atau dikirim</li>
    <li>Jika pesanan sudah diproses atau dikirim, pembatalan tidak dapat dilakukan</li>
    <li>Pengembalian dana untuk pembatalan akan diproses dalam waktu 3-7 hari kerja</li>
</ul>

<h3 style="font-size: 1.5rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #1f2937;">9. Kontak Customer Service</h3>
<p style="margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">Untuk pertanyaan lebih lanjut tentang kebijakan pengembalian dana, silakan hubungi customer service kami melalui:</p>
<ul style="list-style-type: disc; margin-left: 2rem; margin-bottom: 1rem; line-height: 1.75; color: #4b5563;">
    <li>WhatsApp: Tersedia di halaman kontak</li>
    <li>Email: Tersedia di halaman kontak</li>
    <li>Jam operasional: Senin - Jumat, 09:00 - 17:00 WIB</li>
</ul>

<div style="margin-top: 2rem; padding: 1rem; background-color: #fef3c7; border-left: 4px solid: #f59e0b; border-radius: 0.5rem;">
    <p style="font-weight: 600; color: #92400e; margin-bottom: 0.5rem;">⚠️ Catatan Penting:</p>
    <p style="color: #78350f; font-size: 0.875rem; line-height: 1.5;">Kami berhak untuk menolak permintaan pengembalian dana jika tidak memenuhi syarat dan ketentuan yang berlaku. Keputusan kami bersifat final dan tidak dapat diganggu gugat.</p>
</div>

<p style="margin-top: 2rem; font-style: italic; color: #6b7280;">Terakhir diperbarui: {{ date('d F Y') }}</p>
HTML;

        // Save to settings
        Setting::set('terms_and_conditions', $termsContent);
        Setting::set('refund_policy', $refundContent);

        $this->command->info('✅ Static pages content has been seeded successfully!');
        $this->command->info('📄 Terms & Conditions: Created');
        $this->command->info('📄 Refund Policy: Created');
    }
}