<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* KUSTOMISASI UNTUK HALAMAN RIWAYAT */
    .riwayat-wrapper {
        margin-left: 250px; /* 🔥 FIX: Mendorong konten ke kanan agar tidak tertutup/tertimbun sidebar */
        padding: 20px 30px;
        box-sizing: border-box;
    }
    .page-title { 
        color: #f1c40f; 
        font-size: 24px; 
        font-weight: 700; 
        margin-bottom: 5px; 
        margin-top: 0; 
    }
    .page-subtitle { 
        color: #94a3b8; 
        margin-bottom: 25px; 
        font-size: 13px; 
    }
    
    /* CARD BOX PUTIH */
    .card-box { 
        background: #ffffff; 
        color: #1e293b; 
        border-radius: 12px; 
        padding: 24px; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.3); 
        margin-bottom: 30px; 
    }
    .card-box h2 { 
        margin-top: 0; 
        margin-bottom: 15px; 
        color: #0f172a; 
        font-size: 16px; 
        border-left: 4px solid #f1c40f; 
        padding-left: 10px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
    }
    
    /* TABEL MODERN */
    .table-modern { 
        width: 100%; 
        border-collapse: collapse; 
        text-align: left; 
        font-size: 14px; 
    }
    .table-modern th { 
        background-color: #f8fafc; 
        color: #64748b; 
        font-weight: 600; 
        padding: 14px 12px; 
        border-bottom: 2px solid #edf2f7; 
    }
    .table-modern td { 
        padding: 14px 12px; 
        border-bottom: 1px solid #f1f5f9; 
        color: #334155; 
    }
    .table-modern tr:hover { 
        background-color: #f8fafc; 
    }
    
    /* TOMBOL EXCEL */
    .btn-excel { 
        background-color: #2e7d32; 
        color: white; 
        padding: 6px 14px; 
        text-decoration: none; 
        border-radius: 4px; 
        font-size: 13px; 
        font-weight: bold; 
        display: inline-flex; 
        align-items: center; 
        border: none; 
        cursor: pointer; 
    }
    
    /* BADGE INDIKATOR */
    .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-total { background: #e2e8f0; color: #475569; }
    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-warning { background: #fef9c3; color: #a16207; }
</style>

<div class="riwayat-wrapper">
    <h1 class="page-title">Riwayat & Laporan Peminjaman</h1>
    <p class="page-subtitle">Pusat kendali log riwayat peminjaman barang dan rekapitulasi data bulanan</p>

    <!-- TABEL 1: REKAP BULANAN -->
    <div class="card-box">
        <h2>
            <span>📊 Laporan Rekapitulasi Per Bulan</span>
            <a href="<?= base_url('riwayat/export_excel_bulanan') ?>" class="btn-excel">📊 Cetak Laporan Bulanan</a>
        </h2>
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th style="text-align: center;">Total Transaksi</th>
                    <th style="text-align: center;">Sudah Dikembalikan</th>
                    <th style="text-align: center;">Masih Dipinjam (Aktif)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($rekap_bulanan)): ?>
                    <?php foreach($rekap_bulanan as $rb): ?>
                    <tr>
                        <td><strong><?= $rb['bulan'] ?></strong></td>
                        <td style="text-align: center;"><span class="badge badge-total"><?= $rb['total'] ?> Transaksi</span></td>
                        <td style="text-align: center;"><span class="badge badge-success"><?= $rb['dikembalikan'] ?> Alat</span></td>
                        <td style="text-align: center;"><span class="badge badge-warning"><?= $rb['aktif'] ?> Alat</span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center; color: #94a3b8;">Belum ada data bulanan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- TABEL 2: DETAIL LOG RIWAYAT -->
    <div class="card-box">
        <h2>
            <span>📋 Detail Log Transaksi Riwayat</span>
            <a href="<?= base_url('riwayat/export_excel') ?>" class="btn-excel" style="background-color: #1565c0;">📋 Cetak Semua Riwayat</a>
        </h2>
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Barang</th>
                    <th>Nama Peminjam</th>
                    <th>Tanggal Pinjam</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($riwayat_detail)): ?>
                    <?php $no = 1; foreach($riwayat_detail as $rd): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $rd['nama_barang'] ?></td>
                        <td><?= $rd['peminjam'] ?></td>
                        <td><?= date('d/m/Y', strtotime($rd['tgl_pinjam'])) ?></td>
                        <td style="text-align: center;">
                            <span class="badge <?= ($rd['status'] == 'dikembalikan') ? 'badge-success' : 'badge-warning' ?>">
                                <?= ucfirst($rd['status'] ?? 'Dipinjam') ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center; color: #94a3b8;">Tidak ada data riwayat.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>