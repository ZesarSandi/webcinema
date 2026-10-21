<?php
// Validasi ganda jika header bawaan php terlewat di beberapa browser
header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Riwayat_Peminjaman_Cinema.xls");
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        .str { mso-number-format:\@; } /* Mencegah kode QR/Angka berubah jadi format scientific */
        th { background-color: #1a237e; color: white; font-weight: bold; text-align: center; }
        td, th { padding: 8px; border: 1px solid #000; }
    </style>
</head>
<body>

    <div style="text-align: center;">
        <h2>LAPORAN RIWAYAT PEMINJAMAN ALAT BARANG</h2>
        <h3>UKM CINEMA UIN SUTHA JAMBI</h3>
    </div>
    <br>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Alat</th>
                <th>Nama Barang</th>
                <th>Nama Peminjam</th>
                <th>Divisi / Keterangan</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali (Deadline)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($riwayat as $r) : ?>
            <tr>
                <td style="text-align: center;"><?= $no++; ?></td>
                <!-- Class str menjaga teks kode barang agar tidak berantakan di excel -->
                <td class="str"><?= $r['qr_code']; ?></td>
                <td><?= $r['nama_barang']; ?></td>
                <td><?= $r['peminjam']; ?></td>
                <td><?= $r['divisi'] ?? 'Mandiri (Scan QR)'; ?></td>
                <td style="text-align: center;"><?= date('d/m/Y', strtotime($r['tgl_pinjam'])); ?></td>
                <td style="text-align: center;"><?= date('d/m/Y', strtotime($r['deadline'])); ?></td>
                <td style="text-align: center; color: green; font-weight: bold;"><?= ucfirst($r['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>