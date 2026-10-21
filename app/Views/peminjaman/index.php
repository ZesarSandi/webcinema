<main class="content peminjaman">
    <div class="main-inner">
        <h1>Data Peminjaman Aktif</h1>
        <div style="display:flex;align-items:center;gap:12px">
            <p class="subtitle" style="margin:0">Monitoring peminjaman barang secara realtime</p>
            <button class="btn btn-outline" id="btn-selftest-global">Jalankan Tes</button>
        </div>

        <div class="table-wrapper">
            <div class="table-toolbar">
                <input class="table-search" type="search" placeholder="Cari peminjam..." aria-label="Cari">
                <button class="btn btn-outline btn-export">Export CSV</button>
            </div>
            
            <table class="table-peminjaman data-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-barang">Barang</th>
                        <th class="col-peminjam">Peminjam</th>
                        <th class="col-divisi">Divisi</th>
                        <th class="col-tgl">Tgl Pinjam</th>
                        <th class="col-deadline">Deadline</th>
                        <th class="col-status">Status</th>
                        <th class="col-foto">Foto</th>
                        <th class="col-ket">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($peminjaman as $p) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $p['nama_barang']; ?></td>
                        <td><?= $p['peminjam']; ?></td>
                        <td><?= $p['divisi']; ?></td>
                        <td><?= $p['tgl_pinjam']; ?></td>
                        <td><?= $p['deadline']; ?></td>
                        <td><span class="status-badge"><?= $p['status']; ?></span></td>
                        <td><img src="<?= base_url('uploads/bukti/' . $p['foto_bukti']); ?>" width="50"></td>
                        <td><?= $p['keterangan']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>