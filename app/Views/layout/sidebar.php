<aside class="sidebar">
    <ul>
        <li class="<?= ($active == 'dashboard') ? 'active' : '' ?>">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a>
        </li>
        <li class="<?= ($active == 'barang') ? 'active' : '' ?>">
            <a href="<?= base_url('barang') ?>">
                Data Barang <span class="sidebar-badge" id="sidebar-items-count"><?= model('App\Models\BarangModel')->countAllResults() ?></span>
            </a>
        </li>
        <li class="<?= ($active == 'peminjaman') ? 'active' : '' ?>">
            <a href="<?= base_url('peminjaman') ?>">Peminjaman</a>
        </li>
        <li class="<?= ($active == 'riwayat') ? 'active' : '' ?>">
            <a href="<?= base_url('riwayat') ?>">Riwayat & Laporan</a> <!-- Nama menu disesuaikan -->
        </li>
    </ul>
</aside>