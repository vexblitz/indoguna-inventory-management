<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table = 'barang_masuk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_transaksi', 'kode_barang', 'jumlah_masuk', 'stok_saat_ini', 'tanggal_masuk'];


    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getTotal()
    {
        return $this->countAllResults();
    }
}
