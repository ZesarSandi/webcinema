<main class="content">
    <div class="main-inner">
        <div class="page-header">
            <h1>Tambah Barang Baru</h1>
            <a href="<?= base_url('barang') ?>" class="btn btn-outline">Kembali</a>
        </div>

        <div class="modal-panel" style="max-width: 700px; margin-top: 20px;">
            <form action="<?= base_url('barang/simpan') ?>" method="post" class="modal-form" enctype="multipart/form-data">
                <?= csrf_field(); ?> 
                
                <label>Nama Alat
                    <input type="text" name="nama" placeholder="Contoh: Kamera Sony A7III" required>
                </label>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <label>Merk
                        <input type="text" name="merk" placeholder="Contoh: Sony">
                    </label>
                    <label>Kategori
                        <select name="kategori" class="input" style="width: 100%;">
                            <option value="Kamera">Kamera</option>
                            <option value="Lensa">Lensa</option>
                            <option value="Audio">Audio</option>
                            <option value="Lighting">Lighting</option>
                            <option value="Aksesoris">Aksesoris</option>
                        </select>
                    </label>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <label>Kondisi
                        <select name="kondisi" class="input" style="width: 100%;">
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Perbaikan">Dalam Perbaikan</option>
                        </select>
                    </label>
                    <label>Stok
                        <input type="number" name="stok" value="1" min="1" required>
                    </label>
                </div>

                <label>Lokasi Penyimpanan
                    <input type="text" name="lokasi" placeholder="Contoh: Lemari A1">
                </label>

                <label>Keterangan Tambahan
                    <textarea name="keterangan" style="width: 100%; height: 80px;" placeholder="Catatan opsional..."></textarea>
                </label>

                <label>Foto Barang
                    <input type="file" name="foto" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: #fff; box-sizing: border-box; margin-top: 5px;">
                </label>

                <div class="modal-actions" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</main>