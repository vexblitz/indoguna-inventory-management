<?php

namespace App\Controllers;

use App\Models\SatuanBarangModel;
use App\Models\KategoriBarangModel;
use App\Models\BarangModel;
use App\Models\UsersModel;
use App\Models\BarangMasukModel;
use App\Models\BarangKeluarModel;

class DashboardController extends BaseController
{
    protected $SatuanBarangModel;
    protected $KategoriBarangModel;
    protected $BarangModel;
    protected $UsersModel;
    protected $BarangMasukModel;
    protected $BarangKeluarModel;
    protected $db;
    protected $session;
    protected $satuanbarang;

    public function __construct()
    {
        $this->SatuanBarangModel = new SatuanBarangModel();
        $this->KategoriBarangModel = new KategoriBarangModel();
        $this->BarangModel = new BarangModel();
        $this->UsersModel = new UsersModel();
        $this->BarangMasukModel = new BarangMasukModel();
        $this->BarangKeluarModel = new BarangKeluarModel();
        $this->db = \Config\Database::connect(); // Load the database instance
        $this->session = \Config\Services::session(); // Load the session instance
    }

    public function index()
    {

        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);

        // Mengambil semua laporan barang
        $barang_control = $this->BarangModel->findAll();
        // Menghitung total barang masuk
        $totalBarang = count($barang_control);

        // Kategori
        $total_kategori = $this->KategoriBarangModel->getTotalKategori();

        $users = $this->UsersModel->findAll();

        // Menghitung total barang masuk
        $barangMasukData = $this->BarangMasukModel->findAll();
        $barangMasuk = [];
        foreach ($barangMasukData as $data) {
            $barang = $this->BarangModel->where('kode_barang', $data['kode_barang'])->first();
            $data['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';
            $barangMasuk[] = $data;
        }
        $totalBarangMasuk = count($barangMasuk);

        // Menghitung total barang keluar
        $data['total_bk'] = $this->BarangKeluarModel->getTotal();
        $barangKeluarData = $this->BarangKeluarModel->findAll();
        $barangKeluar = [];

        foreach ($barangKeluarData as $data) {
            $barang = $this->BarangModel->where('kode_barang', $data['kode_barang'])->first();
            $data['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';
            $barangKeluar[] = $data;
        }
        $totalbarangKeluar = count($barangKeluar);

        $satuanbarang = $this->SatuanBarangModel->findAll();

        return view('dashboard', [
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
            'barang_control' => $barang_control,
            'total_Barang' => $totalBarang, // Kirimkan total barang  ke view
            'total_kategori' => $total_kategori,
            'total_users' => count($users),
            'total_bm' => $totalBarangMasuk, // Kirimkan total barang masuk ke view
            'barangMasuk' => $barangMasuk,
            'total_bk' => $totalbarangKeluar, // Kirimkan total barang keluar ke view
            'barangKeluar' => $barangKeluar,
            'satuanbarang' => $satuanbarang,
            'total_satuan' => count($satuanbarang),
        ]);
    }
}
