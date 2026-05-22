const express = require('express');
const { default: makeWASocket, useMultiFileAuthState, DisconnectReason, makeCacheableSignalKeyStore } = require('@whiskeysockets/baileys');
const pino = require('pino');
const qrcode = require('qrcode-terminal');

const app = express();
app.use(express.json());

const PORT = process.env.PORT || 8000;
const logger = pino({ level: 'silent' });

let sock = null;
let isConnected = false;
let qrCodeData = null;

async function connectToWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState('./auth_info');

    sock = makeWASocket({
        auth: {
            creds: state.creds,
            keys: makeCacheableSignalKeyStore(state.keys, logger),
        },
        printQRInTerminal: false,
        logger: logger,
    });

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            qrCodeData = qr;
            console.log('\n📱 Scan QR Code di bawah ini dengan WhatsApp Anda:');
            qrcode.generate(qr, { small: true });
            console.log('\nAtau buka http://localhost:' + PORT + '/qr untuk melihat QR code.\n');
        }

        if (connection === 'close') {
            isConnected = false;
            const statusCode = lastDisconnect?.error?.output?.statusCode;
            const shouldReconnect = statusCode !== DisconnectReason.loggedOut;
            console.log('⚠️  Koneksi terputus. Status:', statusCode);
            if (shouldReconnect) {
                console.log('🔄 Mencoba menghubungkan kembali...');
                connectToWhatsApp();
            } else {
                console.log('❌ Logged out. Hapus folder auth_info dan restart untuk login ulang.');
            }
        } else if (connection === 'open') {
            isConnected = true;
            qrCodeData = null;
            console.log('✅ WhatsApp berhasil terhubung!');
        }
    });

    sock.ev.on('messages.upsert', ({ messages }) => {
        // Log incoming messages for debugging
        for (const msg of messages) {
            if (!msg.key.fromMe && msg.message) {
                const sender = msg.key.remoteJid;
                const text = msg.message.conversation || msg.message.extendedTextMessage?.text || '';
                console.log(`📩 Pesan masuk dari ${sender}: ${text}`);
            }
        }
    });
}

// API Endpoints

// Status check
app.get('/status', (req, res) => {
    res.json({
        status: isConnected ? 'connected' : 'disconnected',
        message: isConnected ? 'WhatsApp terhubung' : 'WhatsApp tidak terhubung. Silakan scan QR code.',
    });
});

// QR Code page
app.get('/qr', (req, res) => {
    if (isConnected) {
        return res.send('<html><body style="font-family:sans-serif;text-align:center;padding:50px;"><h1>✅ WhatsApp Sudah Terhubung</h1><p>Tidak perlu scan QR code lagi.</p></body></html>');
    }
    if (!qrCodeData) {
        return res.send('<html><body style="font-family:sans-serif;text-align:center;padding:50px;"><h1>⏳ Menunggu QR Code...</h1><p>Refresh halaman ini dalam beberapa detik.</p><script>setTimeout(()=>location.reload(),3000)</script></body></html>');
    }
    // Generate QR as simple text display
    res.send(`<html><body style="font-family:monospace;text-align:center;padding:20px;background:#1a1a1a;color:#fff;">
        <h1>📱 Scan QR Code dengan WhatsApp</h1>
        <p>Buka WhatsApp > Linked Devices > Link a Device</p>
        <div style="margin:20px auto;padding:20px;background:white;display:inline-block;border-radius:12px;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qrCodeData)}" alt="QR Code" />
        </div>
        <p style="color:#888;">QR Code akan otomatis refresh...</p>
        <script>setTimeout(()=>location.reload(),20000)</script>
    </body></html>`);
});

// Send message endpoint
app.post('/send-message', async (req, res) => {
    try {
        const { phone, message } = req.body;

        if (!phone || !message) {
            return res.status(400).json({
                success: false,
                message: 'Parameter "phone" dan "message" wajib diisi.',
            });
        }

        if (!isConnected || !sock) {
            return res.status(503).json({
                success: false,
                message: 'WhatsApp belum terhubung. Silakan scan QR code terlebih dahulu.',
            });
        }

        // Format phone number: ensure it ends with @s.whatsapp.net
        let formattedPhone = phone.replace(/[^0-9]/g, '');
        if (formattedPhone.startsWith('0')) {
            formattedPhone = '62' + formattedPhone.substring(1);
        }
        if (!formattedPhone.includes('@')) {
            formattedPhone = formattedPhone + '@s.whatsapp.net';
        }

        await sock.sendMessage(formattedPhone, { text: message });

        console.log(`📤 Pesan terkirim ke ${formattedPhone}`);

        res.json({
            success: true,
            message: 'Pesan berhasil dikirim!',
            to: formattedPhone,
        });
    } catch (error) {
        console.error('❌ Gagal mengirim pesan:', error.message);
        res.status(500).json({
            success: false,
            message: 'Gagal mengirim pesan: ' + error.message,
        });
    }
});

// Start server
app.listen(PORT, () => {
    console.log(`\n🚀 WhatsApp Gateway berjalan di http://localhost:${PORT}`);
    console.log('📡 Endpoints:');
    console.log(`   GET  /status       - Cek status koneksi`);
    console.log(`   GET  /qr           - Lihat QR Code`);
    console.log(`   POST /send-message - Kirim pesan WA`);
    console.log('');
    connectToWhatsApp();
});
