const express = require('express');
const QRCode = require('qrcode');
const fs = require('fs');
const path = require('path');

const app = express();
app.use(express.json());

// Pastikan folder untuk menyimpan gambar QR tersedia
const qrFolder = path.join(__dirname, 'public/qrcodes');
if (!fs.existsSync(qrFolder)) {
    fs.mkdirSync(qrFolder, { recursive: true });
}

// Endpoint untuk input barang baru dan generate QR
app.post('/api/barang', async (req, res) => {
    try {
        const { nama, merk, kategori } = req.body;

        // 1. Logika simpan ke database (Simulasi)
        // Misal kita buat ID unik atau kode barang
        const kodeBarang = `BRG-${Date.now()}`; 

        // 2. Data yang akan dimasukkan ke dalam QR Code
        // Bisa berupa teks biasa atau link URL detail barang
        const dataKeQR = JSON.stringify({
            kode: kodeBarang,
            nama: nama,
            merk: merk
        });

        // 3. Nama file gambar QR
        const fileName = `${kodeBarang}.png`;
        const filePath = path.join(qrFolder, fileName);

        // 4. Generate QR Code dan simpan sebagai file
        await QRCode.toFile(filePath, dataKeQR, {
            color: {
                dark: '#1a237e',  // Warna QR (Biru Cinema)
                light: '#ffffff'  // Warna Background
            },
            width: 300
        });

        // Balikan respon ke client
        res.status(201).json({
            status: 'success',
            message: 'Barang berhasil terdaftar dan QR dibuat',
            data: {
                kode: kodeBarang,
                qrPath: `/qrcodes/${fileName}`
            }
        });

    } catch (err) {
        res.status(500).json({ status: 'error', message: err.message });
    }
});

const PORT = 3000;
app.listen(PORT, () => console.log(`Server jalan di http://localhost:${PORT}`));