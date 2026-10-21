<main class="content">
    <div class="main-inner">
        <div class="page-header" style="margin-bottom: 20px;">
            <h1 style="color: #ffd700;">Edit Barang</h1>
            <a href="<?= base_url('barang') ?>" class="btn btn-outline">Kembali</a>
        </div>

        <div class="modal-panel" style="max-width: 700px; margin: 20px auto; background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <form action="<?= base_url('barang/update/' . ($barang['id'] ?? '')) ?>" method="post" class="modal-form">
                <?= csrf_field(); ?>
                
                <label style="display: block; margin-bottom: 15px; color: #6c757d;">Nama Alat
                    <input type="text" name="nama" value="<?= esc($barang['nama'] ?? '') ?>" required 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;">
                </label>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                    <label style="color: #6c757d;">Merk
                        <input type="text" name="merk" value="<?= esc($barang['merk'] ?? '') ?>"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;">
                    </label>
                    <label style="color: #6c757d;">Kategori
                        <select name="kategori" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;">
                            <?php $kat = $barang['kategori'] ?? ''; ?>
                            <option value="Kamera" <?= ($kat == 'Kamera') ? 'selected' : '' ?>>Kamera</option>
                            <option value="Lensa" <?= ($kat == 'Lensa') ? 'selected' : '' ?>>Lensa</option>
                            <option value="Audio" <?= ($kat == 'Audio') ? 'selected' : '' ?>>Audio</option>
                            <option value="Lighting" <?= ($kat == 'Lighting') ? 'selected' : '' ?>>Lighting</option>
                        </select>
                    </label>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                    <label style="color: #6c757d;">Kondisi
                        <select name="kondisi" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;">
                            <?php $kon = $barang['kondisi'] ?? ''; ?>
                            <option value="Baik" <?= ($kon == 'Baik') ? 'selected' : '' ?>>Baik</option>
                            <option value="Rusak Ringan" <?= ($kon == 'Rusak Ringan') ? 'selected' : '' ?>>Rusak Ringan</option>
                        </select>
                    </label>
                    <label style="color: #6c757d;">Stok
                        <input type="number" name="stok" value="<?= esc($barang['stok'] ?? 0) ?>"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;">
                    </label>
                </div>

                <label style="display: block; margin-bottom: 15px; color: #6c757d;">Lokasi Penyimpanan
                    <input type="text" name="lokasi" value="<?= esc($barang['lokasi'] ?? '') ?>"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;">
                </label>

                <div class="modal-actions" style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-weight: bold; border-radius: 12px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</main>