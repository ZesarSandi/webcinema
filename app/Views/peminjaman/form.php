<main class="content peminjaman">
    <div class="main-inner">
        <div class="page-header">
            <h1>Formulir Peminjaman</h1>
            <div class="page-actions">
                <a class="btn btn-outline" href="<?= base_url('peminjaman') ?>">Kembali</a>
            </div>
        </div>

        <section class="qr-scanner-section" style="margin:20px 0;padding:20px;background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08)">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <h2 style="margin:0;font-size:18px">🔲 Scan QR Code Barang</h2>
                <button type="button" class="btn btn-primary" id="btn-toggle-scanner">📷 Buka Scanner</button>
            </div>

            <div id="qr-scanner-container" style="display:none">
                <div style="display:flex;gap:20px;flex-wrap:wrap">
                    <div style="flex:1;min-width:280px">
                        <div style="position:relative;background:#f5f7fa;border-radius:8px;overflow:hidden;border:2px solid #1a237e">
                            <video id="qr-video" autoplay playsinline style="width:100%;display:block;max-width:400px"></video>
                            <canvas id="qr-canvas" style="display:none"></canvas>
                            <div id="qr-overlay" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:200px;height:200px;border:3px solid #4ade80;border-radius:8px;pointer-events:none;box-shadow:0 0 0 9999px rgba(0,0,0,0.5)"></div>
                        </div>
                        <div style="margin-top:8px;display:flex;gap:8px">
                            <button type="button" class="btn btn-outline" id="btn-close-scanner">❌ Tutup</button>
                        </div>
                    </div>
                    <div style="flex:1;min-width:280px">
                        <div id="scanner-status" style="padding:16px;background:#f5f7fa;border-radius:8px;border-left:4px solid #1a237e">
                            <p id="status-text" style="margin:0;font-size:14px;color:#666">Menunggu QR code...</p>
                        </div>
                        <div id="scan-result" style="display:none;margin-top:12px;padding:16px;background:#dcfce7;border-radius:8px;border-left:4px solid #4ade80">
                            <p id="scanned-code" style="margin:0;font-size:14px;color:#166534;word-break:break-all"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal-panel" style="max-width:900px;margin:12px auto">
            <header class="modal-header">
                <h3>Form Peminjaman Barang</h3>
            </header>

            <div class="modal-body" style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">
                <section class="loan-section">
                    <form action="<?= base_url('peminjaman/simpan') ?>" method="post" enctype="multipart/form-data" class="modal-form">
                        <?= csrf_field() ?>
                        
                        <label>Pilih Barang
                            <select id="loan-item-select" name="barang_id" class="input">
    <option value="">-- Pilih Barang --</option>
    <?php foreach($barang as $b) : ?>
        <option value="<?= $b['id']; ?>" data-kode="<?= $b['kode_barang']; ?>">
            <?= $b['nama_barang']; ?>
        </option>
    <?php endforeach; ?>
</select>
                        </label>

                        <label>Nama Peminjam
                            <input type="text" name="peminjam" required>
                        </label>
                        
                        <label>Divisi
                            <input type="text" name="divisi" required>
                        </label>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                            <label>Tanggal Pinjam
                                <input type="date" name="tgl_pinjam" value="<?= date('Y-m-d') ?>" required>
                            </label>
                            <label>Deadline Kembali
                                <input type="date" name="deadline" required>
                            </label>
                        </div>

                        <label>Foto Bukti (Selfie)
                            <input type="file" name="foto_bukti" accept="image/*" capture="user">
                        </label>

                        <label>Keterangan
                            <textarea name="keterangan" class="input" style="width:100%;height:80px"></textarea>
                        </label>

                        <div class="modal-actions">
                            <button type="submit" class="btn btn-primary">Kirim Peminjaman</button>
                        </div>
                    </form>
                </section>

                <aside class="qr-preview-section" style="padding:16px;background:#f5f7fa;border-radius:8px;border:2px solid #e0e0e0; text-align:center">
                    <h4 style="color:#1a237e;font-size:14px">📦 Barang Dipilih</h4>
                    <div id="loan-item-name" style="margin-top:8px;font-weight:700">Belum ada barang</div>
                    </aside>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="<?= base_url('assets/js/qr-scanner.js') ?>"></script>