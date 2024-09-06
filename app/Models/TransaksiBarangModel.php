<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiBarangModel extends Model
{
    protected $table = 'transaksi_barang';
    protected $primaryKey = 'id';

    protected $allowedFields = ['laporan_barang_id', 'stok_masuk', 'stok_keluar', 'tanggal'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
