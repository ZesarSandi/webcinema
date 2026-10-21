<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Helpher yang akan otomatis dimuat di semua controller.
     * Masukkan 'url' agar fungsi base_url() bisa bekerja.
     */
    protected $helpers = ['form', 'url', 'html']; // <--- PASTIKAN BARIS INI ADA DAN TIDAK ADA // DI DEPANNYA

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Jangan hapus baris ini
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }
}