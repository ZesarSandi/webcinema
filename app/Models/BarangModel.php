<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table      = 'barang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'merk', 'kategori', 'kondisi', 'stok', 'lokasi', 'keterangan', 'qr_code', 'foto'];
    protected $useTimestamps = true;
}