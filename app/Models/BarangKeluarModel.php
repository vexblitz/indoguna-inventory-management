<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangKeluarModel extends Model
{
    protected $table = 'barang_keluar';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_transaksi', 'kode_barang', 'jumlah_keluar', 'stok_saat_ini', 'tanggal_keluar'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';


    public function getTotal()
    {
        return $this->countAllResults();
    }
}
