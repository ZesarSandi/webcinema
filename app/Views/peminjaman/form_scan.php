<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman & Pengembalian Mandiri</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
        .container { max-width: 500px; background: white; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 5px; }
        .sub-title { text-align: center; color: #666; font-size: 14px; margin-bottom: 20px; }
        .detail-barang { text-align: center; background: #e9ecef; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .detail-barang img { max-width: 150px; border-radius: 6px; margin-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; color: #555; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-container { display: flex; gap: 10px; margin-top: 25px; }
        .btn-submit { flex: 1; padding: 12px; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; font-weight: bold; transition: background 0.2s; }
        .btn-pinjam { background: #1a237e; }
        .btn-pinjam:hover { background: #0d1b60; }
        .btn-kembali { background: #d32f2f; }
        .btn-kembali:hover { background: #b71c1c; }
        .alert { padding: 12px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center; }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Mandiri QR Code</h2>
    <div class="sub-title">UKM CINEMA</div>
    
    <div class="detail-barang">
        <?php if(!empty($barang['foto'])): ?>
            <img src="<?= base_url('uploads/' . $barang['foto']) ?>" alt="Foto Barang"><br>
        <?php endif; ?>
        
        <strong>Nama Alat:</strong> <?= esc($barang['nama']) ?><br>
        <strong>Merk/Kategori:</strong> <?= esc($barang['merk'] ?? '-') ?><br>
        <strong>Kondisi:</strong> <?= esc($barang['kondisi'] ?? '-') ?><br>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('peminjaman/simpan_mandiri') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <input type="hidden" name="barang_id" value="<?= $barang['id'] ?>">

        <div class="form-group">
            <label for="nama_peminjam">Nama Lengkap Anda:</label>
            <input type="text" name="nama_peminjam" id="nama_peminjam" class="form-control" placeholder="Masukkan nama Anda" required value="<?= old('nama_peminjam') ?>">
        </div>

        <div class="form-group">
            <label for="tanggal_peminjam">Tanggal Peminjaman:</label>
            <input type="date" name="tanggal_pinjam" id="tanggal_peminjam" class="form-control" value="<?= old('tanggal_pinjam', $tanggal_pinjam) ?>" required>
        </div>

        <div class="form-group">
            <label for="tanggal_kembali">Tanggal Pengembalian:</label>
            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="<?= old('tanggal_kembali', $tanggal_kembali) ?>" required>
        </div>

        <div class="form-group">
            <label for="foto_bukti">Upload Foto Bukti:</label>
            <input type="file" name="foto_bukti" id="foto_bukti" class="form-control" accept="image/*" capture="user" required>
            <small style="color: #444; display: block; margin-top: 8px; line-height: 1.4; background: #fff3cd; padding: 8px; border-radius: 4px; border-left: 4px solid #ffc107;">
                <strong>💡 Ketentuan Foto:</strong><br>
                • Jika <strong>MEMINJAM</strong>: Ambil foto <strong>Selfie + Barang</strong> dan wajah Anda.<br>
                • Jika <strong>MENGEMBALIKAN</strong>: Ambil foto <strong>Barang </strong> di kantor.
            </small>
        </div>

        <div class="btn-container">
            <button type="submit" name="aksi" value="pinjam" class="btn-submit btn-pinjam">
                Konfirmasi Peminjaman
            </button>
            <button type="submit" name="aksi" value="kembali" class="btn-submit btn-kembali">
                Konfirmasi Pengembalian
            </button>
        </div>
    </form>
</div>

</body>
</html>