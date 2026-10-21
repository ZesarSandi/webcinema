<?php

namespace App\Controllers;

class Dashboard extends BaseController
{

   public function index()
{
    $db = \Config\Database::connect();

    // Ambil SEMUA data peminjaman untuk kita periksa isinya secara realtime
    $semua_peminjaman = $db->table('peminjaman')
        ->select('peminjaman.*, barang.nama as nama_barang')
        ->join('barang', 'barang.id = peminjaman.barang_id', 'left')
        ->orderBy('peminjaman.id', 'DESC')
        ->get()->getResultArray();

    $data = [
        'title'            => 'Dashboard Monitoring',
        'active'           => 'dashboard',
        'semua_peminjaman' => $semua_peminjaman
    ];

    return view('dashboard/index', $data);
}

}