<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table      = 'peminjaman';
    protected $primaryKey = 'id';
    protected $allowedFields = ['barang_id', 'peminjam', 'divisi', 'tgl_pinjam', 'deadline', 'status', 'foto_bukti', 'keterangan'];
    protected $useTimestamps = true;

    // Fungsi tambahan untuk mengambil nama barang dengan JOIN
    public function getPeminjamanAktif()
    {
        return $this->select('peminjaman.*, barang.nama as nama_barang')
                    ->join('barang', 'barang.id = peminjaman.barang_id')
                    ->where('peminjaman.status', 'aktif')
                    ->findAll();
    }
}