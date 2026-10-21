<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<main class="content">
    <div class="main-inner">
        <h1>📊 Dashboard Inventaris</h1>
        
        <div class="cards dashboard-stats">
            <div class="card stat-item">
                <h3>Total Barang</h3>
                <p class="number">0</p>
            </div>
            </div>

        <div class="table-wrap">
            <h2>Riwayat Terakhir</h2>
            <table class="data-table">
                </table>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>