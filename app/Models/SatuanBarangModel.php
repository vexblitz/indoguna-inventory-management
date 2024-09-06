<?php

namespace App\Models;

use CodeIgniter\Model;

class SatuanBarangModel extends Model
{
    protected $table = 'satuan_barang';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_satuan'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
