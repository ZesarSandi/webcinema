<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


//dahsboard
$routes->get('/dashboard', 'Dashboard::index');

//barang
$routes->get('/barang', 'Barang::index');
$routes->get('/barang/tambah', 'Barang::tambah');
$routes->post('/barang/simpan', 'Barang::simpan');
$routes->get('/barang/edit/(:num)', 'Barang::edit/$1');
$routes->post('/barang/update/(:num)', 'Barang::update/$1');
$routes->get('/barang/hapus/(:num)', 'Barang::hapus/$1');
$routes->get('/riwayat', 'Riwayat::index');

//peminjaman
$routes->get('/peminjaman', 'Peminjaman::index');
$routes->get('/peminjaman/tambah', 'Peminjaman::tambah');
$routes->post('/peminjaman/simpan', 'Peminjaman::simpan'); 
$routes->get('barang/cetak/(:num)', 'Barang::cetak/$1');

//riwayat
$routes->get('/riwayat', 'Riwayat::index');

// Arahkan halaman utama (/) langsung ke Controller Dashboard Anda
$routes->get('/', 'Dashboard::index');

//laporan
// Rute lama laporan dihapus, gantikan dengan rute cetak bulanan dari riwayat
$routes->get('riwayat', 'Riwayat::index');
$routes->get('riwayat/export_excel', 'Riwayat::exportExcel'); // Cetak semua detail
$routes->get('riwayat/export_excel_bulanan', 'Riwayat::exportExcelBulanan'); // Cetak rekap bulanan

// Rute untuk menampilkan form peminjaman saat QR di-scan (menggunakan kode barang)
$routes->get('peminjaman/scan/(:any)', 'Peminjaman::scan/$1');

// Rute untuk memproses simpan data peminjaman mandiri
$routes->post('peminjaman/simpan_mandiri', 'Peminjaman::simpanMandiri');

// Rute untuk Fitur Scan QR Mandiri
$routes->get('peminjaman/scan/(:any)', 'Peminjaman::scan/$1');
$routes->post('peminjaman/simpan_mandiri', 'Peminjaman::simpanMandiri');
$routes->get('peminjaman/sukses', 'Peminjaman::sukses');

// Rute untuk menyelesaikan peminjaman (Tombol Selesai Admin)
$routes->get('peminjaman/kembalikan/(:num)', 'Peminjaman::kembalikan/$1');

// Rute untuk mengunduh laporan riwayat bentuk excel
$routes->get('riwayat/export_excel', 'Riwayat::exportExcel');
