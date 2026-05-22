# WhatsApp Gateway - Toko Online

Gateway WhatsApp menggunakan **Baileys** (Multi-Device) untuk mengirim notifikasi pesanan otomatis.

## Cara Menggunakan

### 1. Install Dependencies
```bash
cd whatsapp-gateway
npm install
```

### 2. Jalankan Server
```bash
npm start
```

### 3. Scan QR Code
- Buka terminal dan scan QR code yang muncul
- Atau buka `http://localhost:8000/qr` di browser
- Gunakan WhatsApp > Linked Devices > Link a Device

### 4. API Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/status` | Cek status koneksi WhatsApp |
| GET | `/qr` | Halaman QR Code untuk login |
| POST | `/send-message` | Kirim pesan WhatsApp |

### Contoh Kirim Pesan
```bash
curl -X POST http://localhost:8000/send-message \
  -H "Content-Type: application/json" \
  -d '{"phone": "08123456789", "message": "Hello dari Toko Online!"}'
```
