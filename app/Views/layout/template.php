<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?> | UKM CINEMA</title>
    <link rel="stylesheet" href="<?= base_url('css/style_dashboard.css'); ?>">
</head>
<body>

<div class="topbar">
    <img src="<?= base_url('img/Cinema.png'); ?>" alt="CINEMA" class="logo">
</div>

<div class="container">
    <aside class="sidebar">
        
        <ul>
            <li class="<?= ($active == 'dashboard') ? 'active' : ''; ?>"><a href="<?= base_url('/'); ?>">Dashboard</a></li>
            <li class="<?= ($active == 'barang') ? 'active' : ''; ?>"><a href="<?= base_url('/barang'); ?>">Data Barang</a></li>
            <li class="<?= ($active == 'pinjan') ? 'active' : ''; ?>"><a href="<?= base_url('/peminjaman'); ?>">Peminjaman</a></li>
            
            <!-- Ganti baris Riwayat & Laporan lama Anda dengan kode di bawah ini -->
            <li class="<?= ($active == 'riwayat') ? 'active' : ''; ?>">
                <a href="<?= base_url('/riwayat'); ?>">Riwayat & Laporan</a>
            </li>
        </ul>

    </aside>

    <?= $this->renderSection('content'); ?>

</div>

<script src="<?= base_url('js/config.js'); ?>"></script>
<script src="<?= base_url('js/app.js'); ?>"></script>
</body>
</html>