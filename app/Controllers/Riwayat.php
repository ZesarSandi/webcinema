<?php

namespace App\Controllers;

class Riwayat extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

    // 1. QUERY DETAIL RIWAYAT (Untuk Tabel Detail)
    $builder = $db->table('peminjaman');
    $builder->select('peminjaman.*, barang.nama as nama_barang, barang.qr_code');
    $builder->join('barang', 'barang.id = peminjaman.barang_id', 'left');
    $builder->orderBy('peminjaman.id', 'DESC');
    $data['riwayat_detail'] = $builder->get()->getResultArray();

    // 2. QUERY REKAP BULANAN (Pindahan dari Laporan)
    $sql_bulanan = "SELECT 
                        DATE_FORMAT(tgl_pinjam, '%M %Y') as bulan,
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'dikembalikan' THEN 1 ELSE 0 END) as dikembalikan,
                        SUM(CASE WHEN status = 'dipinjam' OR status = '' OR status IS NULL THEN 1 ELSE 0 END) as aktif
                    FROM peminjaman
                    GROUP BY DATE_FORMAT(tgl_pinjam, '%Y-%m')
                    ORDER BY YEAR(tgl_pinjam) DESC, MONTH(tgl_pinjam) DESC";
    $data['rekap_bulanan'] = $db->query($sql_bulanan)->getResultArray();

    // Tambahkan baris title di bawah ini agar layout/template tidak error
    $data['title']  = 'Riwayat & Laporan'; 
    $data['active'] = 'riwayat';

    return view('riwayat/index', $data);
    }

    // FUNGSI CETAK LAPORAN REKAP BULANAN
    public function exportExcelBulanan()
    {
        $db = \Config\Database::connect();
        $sql = "SELECT 
                    DATE_FORMAT(tgl_pinjam, '%M %Y') as bulan,
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'dikembalikan' THEN 1 ELSE 0 END) as dikembalikan,
                    SUM(CASE WHEN status = 'dipinjam' OR status = '' OR status IS NULL THEN 1 ELSE 0 END) as aktif
                FROM peminjaman
                GROUP BY DATE_FORMAT(tgl_pinjam, '%Y-%m')
                ORDER BY YEAR(tgl_pinjam) DESC, MONTH(tgl_pinjam) DESC";
        
        $data['rekap'] = $db->query($sql)->getResultArray();

        header("Content-Type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Bulanan_Peminjaman_Cinema.xls");
        
        return view('riwayat/export_excel_bulanan', $data);
    }

    // FUNGSI CETAK DETAIL RIWAYAT (Yang sudah dibuat sebelumnya)
    public function exportExcel()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, barang.nama as nama_barang, barang.qr_code');
        $builder->join('barang', 'barang.id = peminjaman.barang_id', 'left');
        $builder->orderBy('peminjaman.id', 'DESC');
        $data['riwayat'] = $builder->get()->getResultArray();

        header("Content-Type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Detail_Riwayat_Cinema.xls");

        return view('riwayat/export_excel', $data);
    }
}