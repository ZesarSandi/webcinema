<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .dashboard-wrapper {
        margin-left: 250px; 
        padding: 20px 30px;
        box-sizing: border-box;
    }
    .page-title { color: #f1c40f; font-size: 24px; font-weight: 700; margin-bottom: 5px; margin-top: 0; }
    .page-subtitle { color: #94a3b8; margin-bottom: 25px; font-size: 13px; }
    
    .card-box { 
        background: #ffffff; 
        color: #1e293b; 
        border-radius: 12px; 
        padding: 24px; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.3); 
        margin-bottom: 30px; 
                }
    .card-box h2 { margin-top: 0; margin-bottom: 15px; color: #0f172a; font-size: 16px; border-left: 4px solid #f1c40f; padding-left: 10px; }
    
    .table-modern { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
    .table-modern th { background-color: #f8fafc; color: #64748b; font-weight: 600; padding: 14px 12px; border-bottom: 2px solid #edf2f7; }
    .table-modern td { padding: 14px 12px; border-bottom: 1px solid #f1f5f9; color: #334155; }
    .table-modern tr:hover { background-color: #f8fafc; }
    
    .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block; text-transform: capitalize; }
    .badge-warning { background: #fef9c3; color: #a16207; }
    .badge-success { background: #dcfce7; color: #15803d; }
</style>

<div class="dashboard-wrapper">
    <h1 class="page-title">Dashboard Monitoring Realtime</h1>
    <p class="page-subtitle">Pemeriksaan Log Data Peminjaman UKM CINEMA UINSUTHA</p>

    <!-- 1. DAFTAR PEMINJAM AKTIF -->
    <div class="card-box">
        <h2>🔴 Daftar Peminjam Aktif (Alat Sedang Dibawa)</h2>
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Peminjam</th>
                    <th>Nama Alat / Barang</th>
                    <th>Tanggal Pinjam</th>
                    <th style="text-align: center;">Isi Status di Database</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no_aktif = 1; 
                $has_aktif = false;
                foreach($semua_peminjaman as $sp): 
                    // Jika statusnya BUKAN dikembalikan, tampilkan di sini
                    if (strtolower($sp['status']) != 'dikembalikan'): 
                        $has_aktif = true;
                ?>
                    <tr>
                        <td><?= $no_aktif++ ?></td>
                        <td><strong><?= $sp['peminjam'] ?></strong></td>
                        <td><?= $sp['nama_barang'] ?></td>
                        <td><?= date('d/m/Y', strtotime($sp['tgl_pinjam'])) ?></td>
                        <td style="text-align: center;">
                            <span class="badge badge-warning">
                                <?= !empty($sp['status']) ? $sp['status'] : 'KOSONG / NULL'; ?>
                            </span>
                        </td>
                    </tr>
                <?php 
                    endif;
                endforeach; 
                if(!$has_aktif):
                ?>
                    <tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada peminjaman aktif.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 2. RIWAYAT PENGEMBALIAN TERAKHIR -->
    <div class="card-box">
        <h2>✅ Riwayat Pengembalian Terakhir</h2>
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Peminjam</th>
                    <th>Nama Alat / Barang</th>
                    <th>Tanggal Pinjam</th>
                    <th style="text-align: center;">Isi Status di Database</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no_kembali = 1; 
                $has_kembali = false;
                foreach($semua_peminjaman as $sp): 
                    // Jika status mengandung kata 'kembali' atau 'dikembalikan'
                    if (strpos(strtolower($sp['status']), 'kembali') !== false): 
                        $has_kembali = true;
                ?>
                    <tr>
                        <td><?= $no_kembali++ ?></td>
                        <td><strong><?= $sp['peminjam'] ?></strong></td>
                        <td><?= $sp['nama_barang'] ?></td>
                        <td><?= date('d/m/Y', strtotime($sp['tgl_pinjam'])) ?></td>
                        <td style="text-align: center;">
                            <span class="badge badge-success">
                                <?= $sp['status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php 
                    endif;
                endforeach; 
                if(!$has_kembali):
                ?>
                    <tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada riwayat pengembalian barang yang terdeteksi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>