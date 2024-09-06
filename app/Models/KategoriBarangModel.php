<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriBarangModel extends Model
{
    protected $table = 'kategori_barang';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_kategori'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';


    // Method untuk menghitung total Kategori

    public function getTotalKategori()
    {
        return $this->countAllResults();
    }
}
