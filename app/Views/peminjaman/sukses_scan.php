<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aksi Berhasil</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; text-align: center; padding: 5px; margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 400px; width: 100%; box-sizing: border-box; }
        .icon { font-size: 60px; color: #2e7d32; margin-bottom: 15px; }
        h2 { color: #333; margin-bottom: 10px; }
        p { color: #666; font-size: 15px; line-height: 1.4; margin-bottom: 25px; }
        .btn { display: inline-block; padding: 12px 24px; background: #1a237e; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .btn:hover { background: #0d1b60; }
    </style>
</head>
<body>

<div class="card">
    <div class="icon">✅</div>
    <h2>Aksi Berhasil!</h2>
    
    <!-- Menampilkan pesan dinamis, apakah dia meminjam atau mengembalikan -->
    <p>
        <?= session()->getFlashdata('pesan_sukses') ?? 'Data transaksi mandiri Anda telah berhasil dicatat ke dalam sistem UKM CINEMA.' ?>
    </p>

    <a href="javascript:void(0);" onclick="window.close();" class="btn">Tutup Halaman</a>
</div>

</body>
</html>