<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Label - <?= $barang['nama'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #fafafa; }
        .kartu-qr { width: 350px; border: 2px dashed #333; padding: 20px; border-radius: 10px; background: #fff; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .judul { font-size: 18px; font-weight: bold; margin-bottom: 5px; color: #1a237e; border-bottom: 2px solid #1a237e; padding-bottom: 5px; }
        .sub-judul { font-size: 12px; color: #666; margin-bottom: 15px; text-transform: uppercase; }
        .qr-image { margin: 15px 0; padding: 10px; border: 1px solid #eee; display: inline-block; background: #fff; }
        .info-barang { text-align: left; margin-top: 15px; font-size: 13px; color: #333; background: #f9f9f9; padding: 10px; border-radius: 5px; }
        .info-barang table { width: 100%; border-collapse: collapse; }
        .info-barang td { padding: 4px 0; vertical-align: top; }
        .kode-text { font-family: 'Courier New', Courier, monospace; font-weight: bold; font-size: 16px; color: #333; letter-spacing: 1px; }
        @media print {
            body { background: none; height: auto; }
            .kartu-qr { border: 2px solid #000; box-shadow: none; page-break-inside: avoid; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="kartu-qr">
        <div class="judul">UKM CINEMA UINSUTHA</div>
        <div class="sub-judul">Label Inventaris Barang</div>
        
        <div class="qr-image">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= $barang['qr_code'] ?>" alt="QR Code Barang">
        </div>
        <div class="kode-text"><?= $barang['qr_code'] ?></div>

        <div class="info-barang">
            <table>
                <tr><td style="width: 35%; font-weight:bold;">Nama Alat</td><td>: <?= $barang['nama'] ?></td></tr>
                <tr><td style="font-weight:bold;">Merk</td><td>: <?= $barang['merk'] ?></td></tr>
                <tr><td style="font-weight:bold;">Kategori</td><td>: <?= $barang['kategori'] ?></td></tr>
                <tr><td style="font-weight:bold;">Lokasi</td><td>: <?= $barang['lokasi'] ?></td></tr>
                <tr><td style="font-weight:bold;">Kondisi</td><td>: <?= $barang['kondisi'] ?></td></tr>
            </table>
        </div>
        
        <button class="no-print" onclick="window.print()" style="margin-top: 20px; padding: 8px 15px; background: #1a237e; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Cetak Label</button>
    </div>

</body>
</html>