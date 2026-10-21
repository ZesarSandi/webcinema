<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Barang extends BaseController
{
    protected $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    // Menampilkan halaman daftar barang
    public function index()
    {
        $data = [
            'title'  => 'Data Barang | UKM CINEMA',
            'active' => 'barang',
            'barang' => $this->barangModel->findAll()
        ];
        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('barang/index', $data)
             . view('layout/footer');
    }

    // Menampilkan form tambah barang
    public function tambah()
    {
        $data = [
            'title'  => 'Tambah Barang | UKM CINEMA',
            'active' => 'barang'
        ];
        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('barang/form_tambah', $data)
             . view('layout/footer');
    }

    // Memproses penyimpanan data ke database
    public function simpan()
{
    $kodeQrOtomatis = 'BRG-' . time(); 
    
    // Logika Handle Upload Gambar
    $fileFoto = $this->request->getFile('foto');
    $namaFoto = 'default.jpg'; // nama bawaan jika user tidak upload foto
    
    if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
        $namaFoto = $fileFoto->getRandomName();
        // Memindahkan file ke folder public/uploads/
        $fileFoto->move(FCPATH . 'uploads', $namaFoto);
    }

    $this->barangModel->save([
        'nama'         => $this->request->getPost('nama'),
        'merk'         => $this->request->getPost('merk'),
        'kategori'     => $this->request->getPost('kategori'),
        'kondisi'      => $this->request->getPost('kondisi'),
        'stok'         => $this->request->getPost('stok'),
        'lokasi'       => $this->request->getPost('lokasi'),
        'keterangan'   => $this->request->getPost('keterangan'),
        'qr_code'      => $kodeQrOtomatis,
        'foto'         => $namaFoto // Simpan nama file ke database
    ]);

    return redirect()->to(base_url('barang'))->with('pesan', 'Barang berhasil ditambahkan!');
}
    // Fungsi untuk menampilkan halaman form edit
public function edit($id)
{
    $barang = $this->barangModel->find($id);
    
    if (!$barang) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Barang ID $id tidak ada.");
    }

    $data = [
        'title'  => 'Edit Barang | UKM CINEMA',
        'active' => 'barang',
        'barang' => $barang
    ];

    return view('layout/header', $data)
         . view('layout/sidebar', $data)
         . view('barang/form_edit', $data)
         . view('layout/footer');
}

// Fungsi untuk menyimpan perubahan data ke database
public function update($id)
{
    $this->barangModel->update($id, [
        'nama'     => $this->request->getPost('nama'),
        'jenis'    => $this->request->getPost('jenis'),
        'kondisi'  => $this->request->getPost('kondisi'),
        // tambahkan kolom lain sesuai kebutuhan
    ]);

    return redirect()->to(base_url('barang'))->with('pesan', 'Data berhasil diubah');
}

// Fungsi untuk menghapus data
public function hapus($id)
{
    $this->barangModel->delete($id);
    return redirect()->to(base_url('barang'))->with('pesan', 'Data berhasil dihapus');
}

public function cetak($id)
{
    $data['barang'] = $this->barangModel->find($id);
    if (!$data['barang']) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Barang tidak ditemukan");
    }
    return view('barang/cetak', $data);
}
}