<!DOCTYPE html>
<html>
<head>
    <style>
        th { background-color: #f1c40f; color: black; font-weight: bold; text-align: center; }
        td, th { padding: 8px; border: 1px solid #000; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <h2>REKAPITULASI LAPORAN PEMINJAMAN PER BULAN</h2>
        <h3>UKM CINEMA UIN SUTHA JAMBI</h3>
    </div>
    <br>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan & Tahun</th>
                <th>Total Transaksi Peminjaman</th>
                <th>Jumlah Sudah Kembali</th>
                <th>Jumlah Masih Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($rekap as $r) : ?>
            <tr>
                <td style="text-align: center;"><?= $no++; ?></td>
                <td><b><?= $r['bulan']; ?></b></td>
                <td style="text-align: center;"><?= $r['total']; ?> Kali</td>
                <td style="text-align: center; color: green;"><?= $r['dikembalikan']; ?> Alat</td>
                <td style="text-align: center; color: orange;"><?= $r['aktif']; ?> Alat</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>