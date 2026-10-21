<main class="content data-barang">
    <div class="main-inner">
        <div class="page-header">
            <h1>Data Barang</h1>
            <div class="page-actions">
                <<div class="items-summary" style="margin-right:12px">Total barang: <strong id="total-items-count"><?= count($barang); ?></strong></div>
                <div class="table-toolbar">
                    <input class="table-search" type="search" placeholder="Cari nama, jenis, atau kondisi..." aria-label="Cari">
                    <select class="page-size" aria-label="Baris per halaman">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    <button class="btn btn-outline btn-export">Export CSV</button>
                </div>
                <a href="<?= base_url('barang/tambah') ?>" class="btn btn-primary btn-add">+ Tambah Barang</a>  
                <button class="btn btn-outline btn-seed-items" type="button" style="margin-left:8px">Isi contoh</button>
            </div>
        </div>
        <p id="table-desc" class="sr-only">Tabel inventaris berisi nomor, foto, nama barang, kondisi, jenis, tanggal registrasi, dan aksi.</p>
        <div class="table-controls">
            <div class="table-pagination" aria-hidden="true"></div>
        </div>

        <div class="modal" id="modal-tambah" aria-hidden="true">
            <div class="modal-overlay" data-close></div>
            <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="modal-title" style="max-width:900px">
                <header class="modal-header">
                    <h3 id="modal-title">Tambah Barang</h3>
                    <button class="modal-close" aria-label="Tutup">×</button>
                </header>

                <div class="modal-body" style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">
                
                    <form id="form-tambah" class="modal-form" action="<?= base_url('barang/simpan') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <label>
                            Foto (drag & drop atau pilih file)
                            <div class="dropzone" id="dropzone">
                                <input type="file" accept="image/*" name="foto" id="foto_file" hidden>
                                <div class="dropzone-inner">Tarik gambar di sini atau <button type="button" class="btn btn-outline btn-file">Pilih File</button></div>
                            </div>
                            <div class="preview" id="foto_preview" aria-hidden="true"></div>
                        </label>

                        <label>
                            Nama Barang
                            <input type="text" name="nama" placeholder="Nama barang" required>
                        </label> 

                        <label>
                            Kondisi
                            <select name="kondisi" required>
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="perbaikan">Perbaikan</option>
                            </select>
                        </label>

                        <label>
                            Jenis
                            <input type="text" name="jenis" placeholder="Jenis barang" required>
                        </label>

                        <label>
                            Tanggal Regis
                            <input type="date" name="tanggal" required>
                        </label>

                        <input type="hidden" id="qr-code-input" name="qr_code" value="">

                        <div class="modal-actions">
                            <button type="button" class="btn btn-outline" data-close>Batalkan</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>

                        <p class="modal-fallback">Jika tombol tidak bekerja, gunakan halaman <a href="<?= base_url('barang/tambah') ?>">Tambah Barang</a>.</p>
                    </form>

                    <div class="qr-preview-section" style="display:flex;flex-direction:column;gap:16px;padding:16px;background:#f5f7fa;border-radius:8px;border:2px solid #e0e0e0;min-height:300px">
                        <div style="text-align:center">
                            <h4 style="margin:0 0 12px 0;color:#1a237e;font-size:14px">📱 QR Code</h4>
                            
                            <div id="qr-loading" style="text-align:center;display:none">
                                <div style="font-size:28px;animation:spin 2s linear infinite">⏳</div>
                                <p style="margin:8px 0;color:#666;font-size:12px">Generate...</p>
                            </div>

                            <div id="qr-result" style="display:none">
                                <img id="qr-image" src="" alt="QR Code" style="max-width:100%;border-radius:8px;border:2px solid #1a237e;background:white;padding:8px;box-sizing:border-box">
                                <div style="margin-top:8px;font-weight:600;font-family:monospace;font-size:12px;color:#1a237e;word-break:break-all" id="qr-code-display"></div>
                            </div>

                            <div id="qr-empty" style="padding:30px 12px;color:#999">
                                <div style="font-size:32px;margin-bottom:8px">📷</div>
                                <p style="margin:0;font-size:12px">Isi nama barang untuk<br>auto-generate QR</p>
                            </div>
                        </div>

                        <div id="qr-actions" style="display:none;display:flex;flex-direction:column;gap:8px">
                            <button type="button" class="btn btn-primary" id="qr-download-btn" style="font-size:12px;padding:8px 12px">💾 Download</button>
                            <button type="button" class="btn btn-outline" id="qr-print-btn" style="font-size:12px;padding:8px 12px">🖨️ Cetak</button>
                            <button type="button" class="btn btn-outline" id="qr-copy-code-btn" style="font-size:12px;padding:8px 12px">📋 Copy</button>
                        </div>
                    </div>

                </div>

                <style>
                    @keyframes spin {
                        from { transform: rotate(0deg); }
                        to { transform: rotate(360deg); }
                    }
                    @media (max-width: 768px) {
                        .modal-panel {
                            max-width: 95% !important;
                        }
                        .modal-body {
                            grid-template-columns: 1fr !important;
                        }
                        .qr-preview-section {
                            display: none;
                        }
                    }
                </style>
            </div>
        </div>

        <div class="modal" id="modal-label" aria-hidden="true">
            <div class="modal-overlay" data-close></div>
            <div class="modal-panel" role="dialog" aria-modal="true">
                <header class="modal-header">
                    <h3 id="modal-label-title">Label QR</h3>
                    <button class="modal-close" aria-label="Tutup">×</button>
                </header>
                <div class="modal-body" id="modal-label-body">
                    <div style="text-align:center">
                        <img id="label-qr-img" src="" alt="QR" style="max-width:240px;border-radius:6px">
                        <div style="margin-top:8px;font-weight:700" id="label-code-text"></div>
                        <p class="mt-6">Scan dengan kamera HP untuk langsung membuka form peminjaman.</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-outline" data-close>Close</button>
                    <button class="btn btn-primary" id="label-print">Cetak</button>
                </div>
            </div>
        </div>

        <div class="table-wrap">
            <caption class="sr-only">Daftar inventaris barang UKM CINEMA</caption>
            <table class="data-table" aria-describedby="table-desc">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-foto">Foto</th>
                        <th class="col-nama">Nama Barang</th>
                        <th class="col-kondisi">Kondisi</th>
                        <th class="col-jenis">Jenis</th>
                        <th class="col-tgl">Tanggal Regis</th>
                        <th class="col-kode">Kode</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($barang as $b) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <?php if (!empty($b['foto']) && $b['foto'] != 'default.jpg') : ?>
                                <img src="<?= base_url('uploads/' . $b['foto']); ?>" alt="foto" style="width:50px; height:50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                            <?php else : ?>
                                <div style="width:50px; height:50px; background:#e9ecef; border-radius:4px; display:flex; align-items:center; justify-content:center; color:#6c757d; font-size:10px; border: 1px solid #ddd;">No Foto</div>
                            <?php endif; ?>
                        </td>
                        <td><?= $b['nama']; ?></td>
                        <td><span class="kondisi <?= $b['kondisi']; ?>"><?= ucfirst($b['kondisi']); ?></span></td>
                        <td><?= $b['jenis']; ?></td>
                        <td><?= $b['tanggal_regis']; ?></td>
                        <td class="col-kode" style="text-align: center;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data=<?= $b['qr_code']; ?>" 
                                 alt="QR Code" style="border: 1px solid #ddd; padding: 4px; background: #fff; border-radius: 4px;">
                            <br>
                            <small style="font-family: monospace; color: #666; font-weight: bold;"><?= $b['qr_code']; ?></small>
                        </td>
                        <td>
                            <div class="table-actions" style="display: flex; gap: 5px;">
                                <a href="<?= base_url('barang/edit/' . $b['id']) ?>" class="btn btn-outline action-edit" style="color: #0d6efd; border-color: #0d6efd; padding: 5px 10px; font-size: 12px; text-decoration: none; border-radius: 4px;">Edit</a>
                                
                                <a href="<?= base_url('barang/hapus/' . $b['id']) ?>" class="btn btn-outline action-delete" 
                                   style="color: #dc3545; border-color: #dc3545; padding: 5px 10px; font-size: 12px; text-decoration: none; border-radius: 4px;" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>

                                <a href="<?= base_url('barang/cetak/' . $b['id']) ?>" target="_blank" class="btn btn-outline action-print" 
                                   style="color: #28a745; border-color: #28a745; padding: 5px 10px; font-size: 12px; text-decoration: none; border-radius: 4px; font-weight: bold;">Cetak</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>