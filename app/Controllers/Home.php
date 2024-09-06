<?php

namespace App\Controllers;

use App\Models\SatuanBarangModel;
use App\Models\KategoriBarangModel;
use App\Models\BarangModel;
use App\Models\UsersModel;
use App\Models\BarangMasukModel;
use App\Models\BarangKeluarModel;

class Home extends BaseController
{
    protected $SatuanBarangModel;
    protected $KategoriBarangModel;
    protected $BarangModel;
    protected $UsersModel;
    protected $BarangMasukModel;
    protected $BarangKeluarModel;

    public function __construct()
    {
        $this->SatuanBarangModel = new SatuanBarangModel();
        $this->KategoriBarangModel = new KategoriBarangModel();
        $this->BarangModel = new BarangModel();
        $this->UsersModel = new UsersModel();
        $this->BarangMasukModel = new BarangMasukModel();
        $this->BarangKeluarModel = new BarangKeluarModel();
    }
    public function index()
    {

        // Laporan Stok
        $total_barang = $this->BarangModel->getTotalBarang();

        // Satuan
        $total_satuan = $this->SatuanBarangModel->getTotalSatuan();

        // Kategori
        $total_kategori = $this->KategoriBarangModel->getTotalKategori();

        // Users
        $users = $this->UsersModel->findAll();

        // Laporan Barang Masuk
        $total_bm = $this->BarangMasukModel->getTotal();

        // Laporan Barang Keluar
        $total_bk = $this->BarangKeluarModel->getTotal();

        return view(
            'dashboard',
            [
                'total_satuan' => $total_satuan,
                'total_kategori' => $total_kategori,
                'total_barang' => $total_barang,
                'total_users' => count($users),
                'total_bm' => $total_bm,
                'total_bk' => $total_bk,
            ]
        );
    }
}
