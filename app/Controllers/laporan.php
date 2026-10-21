<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Query rekap bulanan (Raw SQL aman dari pemotongan string)
        $sql = "SELECT 
                    DATE_FORMAT(tgl_pinjam, '%M %Y') as bulan,
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'dikembalikan' THEN 1 ELSE 0 END) as dikembalikan,
                    SUM(CASE WHEN status = 'dipinjam' OR status = '' OR status IS NULL THEN 1 ELSE 0 END) as aktif
                FROM peminjaman
                GROUP BY DATE_FORMAT(tgl_pinjam, '%Y-%m')
                ORDER BY YEAR(tgl_pinjam) DESC, MONTH(tgl_pinjam) DESC";

        $data['rekap'] = $db->query($sql)->getResultArray();
        
        // 2. KODE TAMBAHAN: Mengambil data barang untuk mengisi dropdown "Filter Alat"
        $data['barang'] = $db->table('barang')->orderBy('nama', 'ASC')->get()->getResultArray();
        
        // Indikator menu aktif di sidebar
        $data['active'] = 'laporan';

        return view('laporan/index', $data);
    }
}