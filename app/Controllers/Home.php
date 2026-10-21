<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'active' => 'dashboard'
        ];
        return view('pages/dashboard', $data);
    }

    public function barang()
    {
        $data = [
            'title' => 'Data Barang',
            'active' => 'barang'
        ];
        return view('pages/data_barang', $data); // Buat file ini di app/Views/pages/
    }
}