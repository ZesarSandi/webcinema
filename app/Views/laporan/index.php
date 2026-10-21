<main class="content laporan">
    <div class="main-inner">
        <h1>Laporan Bulanan</h1>
        <p class="subtitle">Ringkasan peminjaman per bulan</p>

        <div class="table-controls">
            <div class="table-toolbar">
                <label>Filter tahun
                    <select class="report-year" id="filter-tahun">
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                    </select>
                </label>
                <label style="margin-left:12px">Filter Alat
                    <select class="report-item" id="filter-alat">
                        <option value="">Semua Alat</option>
                        <?php foreach($barang as $b): ?>
                             <option value="<?= $b['id'] ?>"><?= $b['nama'] ?></option> 
                        <?php endforeach; ?>
                    </select>
                </label>
                <button class="btn btn-outline btn-export">Export CSV</button>
                <button class="btn btn-outline btn-pdf" style="margin-left:10px">Export PDF</button>
            </div>
        </div>

        <div class="chart-wrap" style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
            <canvas id="laporan-chart" width="800" height="240"></canvas>
        </div>

        <div class="table-wrap">
            <table class="data-table table-laporan">
                <thead>
                    <tr>
                        <th class="col-month">Bulan</th>
                        <th class="col-total">Total Pinjam</th>
                        <th class="col-returned">Dikembalikan</th>
                        <th class="col-active">Masih Dipinjam</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($rekap)): ?>
                        <?php foreach($rekap as $r): ?>
                        <tr>
                            <td><strong><?= $r['bulan'] ?></strong></td>
                            <td><?= $r['total'] ?></td>
                            <td><span style="color: green;"><?= $r['dikembalikan'] ?? 0 ?></span></td>
                            <td><span style="color: orange;"><?= $r['aktif'] ?? 0 ?></span></td>
                            <td>
                                <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;">Detail</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">Data tahun ini belum tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Logic sederhana untuk menjalankan Chart.js dengan data dari PHP
    const ctx = document.getElementById('laporan-chart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($rekap, 'bulan')) ?>,
            datasets: [{
                label: 'Total Peminjaman',
                data: <?= json_encode(array_column($rekap, 'total')) ?>,
                backgroundColor: '#1a237e'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>