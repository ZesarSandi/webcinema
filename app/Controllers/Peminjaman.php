<?php

namespace App\Controllers;

// Kita panggil dua model sekaligus
use App\Models\PeminjamanModel;
use App\Models\BarangModel; 

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $barangModel;

    public function __construct()
    {
        // Kita siapkan kedua modelnya di sini
        $this->peminjamanModel = new PeminjamanModel();
        $this->barangModel = new BarangModel();
    }

    // Ini untuk melihat DAFTAR peminjaman (Tabel)
    public function index()
    {
        $data = [
            'title'      => 'Monitoring Peminjaman | UKM CINEMA',
            'active'     => 'peminjaman',
            'peminjaman' => $this->peminjamanModel->getPeminjamanAktif()
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('peminjaman/index', $data)
             . view('layout/footer');
    }

    // Ini untuk menampilkan FORM scan QR (Formulir Admin/Biasa)
    public function tambah()
    {
        $data = [
            'title'  => 'Formulir Peminjaman | UKM CINEMA',
            'active' => 'peminjaman',
            'barang' => $this->barangModel->findAll(), // Mengambil data barang untuk dropdown
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('peminjaman/form', $data) 
             . view('layout/footer');
    }

    // Proses simpan dari Form Admin/Biasa
    public function simpan()
    {
        // Tangkap data dari form
        $data = [
            'barang_id'  => $this->request->getPost('barang_id'),
            'peminjam'   => $this->request->getPost('peminjam'),
            'divisi'     => $this->request->getPost('divisi'),
            'tgl_pinjam' => $this->request->getPost('tgl_pinjam'),
            'deadline'   => $this->request->getPost('deadline'),
            'keterangan' => $this->request->getPost('keterangan'),
            'status'     => 'aktif'
        ];

        // Proses upload foto jika ada
        $fileFoto = $this->request->getFile('foto_bukti');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $newName = $fileFoto->getRandomName();
            $fileFoto->move('uploads/bukti', $newName);
            $data['foto_bukti'] = $newName;
        }

        $this->peminjamanModel->save($data);

        return redirect()->to(base_url('peminjaman'))->with('pesan', 'Peminjaman berhasil dicatat!');
    }

    // =======================================================
    // LOGIKA QR MANDIRI: DUA TOMBOL + VALIDASI MAKS 7 HARI
    // =======================================================

    // 1. Menampilkan Form Mandiri (Tanggal Tidak Lagi Readonly)
    public function scan($kode_barang)
    {
        $barang = $this->barangModel->where('qr_code', $kode_barang)->first();

        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Barang tidak ditemukan atau QR Code tidak valid.");
        }

        // Set default value tanggal hari ini dan +7 hari ke depan (bisa diubah user di form)
        $tanggal_pinjam  = date('Y-m-d');
        $tanggal_kembali = date('Y-m-d', strtotime('+7 days'));

        $data = [
            'barang'          => $barang,
            'tanggal_pinjam'  => $tanggal_pinjam,
            'tanggal_kembali' => $tanggal_kembali
        ];

        return view('peminjaman/form_scan', $data);
    }

    // 2. Memproses Simpan Berdasarkan Tombol yang Ditekan (Pinjam / Kembali)
    public function simpanMandiri()
    {
        // Tangkap aksi tombol (apakah 'pinjam' atau 'kembali')
        $aksi = $this->request->getPost('aksi');

        // Validasi inputan dasar
        if (!$this->validate([
            'nama_peminjam' => 'required|min_length[3]',
            'foto_bukti'    => 'uploaded[foto_bukti]|max_size[foto_bukti,3072]|is_image[foto_bukti]'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses! Pastikan Nama terisi dan Foto Bukti sudah dipilih.');
        }

        // Tangkap inputan tanggal dari user
        $tgl_pinjam  = $this->request->getPost('tanggal_pinjam');
        $tgl_kembali = $this->request->getPost('tanggal_kembali');

        // LOGIKA VALIDASI MAKSIMAL 7 HARI
        $date1 = new \DateTime($tgl_pinjam);
        $date2 = new \DateTime($tgl_kembali);
        
        if ($date2 < $date1) {
            return redirect()->back()->withInput()->with('error', 'Gagal! Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.');
        }
        
        $interval = $date1->diff($date2);
        $selisih_hari = $interval->days;

        if ($selisih_hari > 7) {
            return redirect()->back()->withInput()->with('error', 'Gagal! Jangka waktu peminjaman maksimal adalah 7 hari.');
        }

        // Proses upload file foto (Baik selfie ataupun foto barang bersama orang)
        $fileFoto = $this->request->getFile('foto_bukti');
        $newName = '';
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $newName = $fileFoto->getRandomName();
            $fileFoto->move('uploads/bukti', $newName);
        }

        $barang_id     = $this->request->getPost('barang_id');
        $nama_peminjam = $this->request->getPost('nama_peminjam');

        // JIKA USER MENEKAN TOMBOL PENGEMBALIAN
        if ($aksi === 'kembali') {
            // Cari data peminjaman milik orang tersebut yang statusnya masih 'aktif'
            $peminjamanAktif = $this->peminjamanModel->where([
                'barang_id' => $barang_id,
                'peminjam'  => $nama_peminjam,
                'status'    => 'aktif'
            ])->first();

            // Jika tidak ketemu berdasarkan nama yang sama persis, cari data aktif paling terakhir dari barang tersebut
            if (!$peminjamanAktif) {
                $peminjamanAktif = $this->peminjamanModel->where([
                    'barang_id' => $barang_id,
                    'status'    => 'aktif'
                ])->orderBy('id', 'DESC')->first();
            }

            if ($peminjamanAktif) {
                // Update status data lama menjadi 'dikembalikan' agar masuk ke riwayat pengembalian
                $this->peminjamanModel->save([
                    'id'         => $peminjamanAktif['id'],
                    'status'     => 'dikembalikan', // Status berubah agar pindah ke riwayat pengembalian
                    'tgl_pinjam' => $tgl_pinjam,
                    'deadline'   => $tgl_kembali,
                    'foto_bukti' => $newName // Foto ter-update menggunakan foto kondisi barang saat kembali
                ]);
            } else {
                // Jika tidak ada data pinjam aktif di sistem, kita buat baris data baru langsung berstatus dikembalikan
                $this->peminjamanModel->save([
                    'barang_id'  => $barang_id,
                    'peminjam'   => $nama_peminjam,
                    'divisi'     => 'Mandiri (Scan QR)',
                    'tgl_pinjam' => $tgl_pinjam,
                    'deadline'   => $tgl_kembali,
                    'keterangan' => 'Pengembalian langsung via scan QR Code.',
                    'status'     => 'dikembalikan',
                    'foto_bukti' => $newName
                ]);
            }

            return redirect()->to(base_url('peminjaman/sukses'))->with('pesan_sukses', 'Pengembalian barang berhasil dicatat!');

        } else {
            // JIKA USER MENEKAN TOMBOL PEMINJAMAN (AKSI PINJAM)
            $this->peminjamanModel->save([
                'barang_id'  => $barang_id,
                'peminjam'   => $nama_peminjam,
                'divisi'     => 'Mandiri (Scan QR)',
                'tgl_pinjam' => $tgl_pinjam,
                'deadline'   => $tgl_kembali,
                'keterangan' => 'Peminjaman kilat mandiri via scan QR Code.',
                'status'     => 'aktif', // Status aktif masuk ke daftar monitoring peminjaman
                'foto_bukti' => $newName // Foto terisi foto selfie peminjam
            ]);

            return redirect()->to(base_url('peminjaman/sukses'))->with('pesan_sukses', 'Peminjaman barang berhasil dicatat!');
        }
    }

    public function sukses()
    {
        return view('peminjaman/sukses_scan');
    }

    // Fungsi untuk mengubah status barang menjadi kembali dari halaman Admin/Monitoring
    public function kembalikan($id)
    {
        // Cari data peminjaman berdasarkan ID untuk memastikan datanya ada
        $peminjaman = $this->peminjamanModel->find($id);

        if (!$peminjaman) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data peminjaman tidak ditemukan.");
        }

        // Update status menjadi 'dikembalikan' agar pindah dari monitoring aktif ke riwayat
        $this->peminjamanModel->save([
            'id'     => $id,
            'status' => 'dikembalikan'
        ]);

        // Kembalikan ke halaman daftar monitoring peminjaman dengan pesan sukses
        return redirect()->to(base_url('peminjaman'))->with('pesan', 'Peminjaman berhasil diselesaikan, barang telah dikembalikan!');
    }
}