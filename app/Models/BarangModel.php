<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id';

    protected $allowedFields = ['kode_barang', 'nama_barang', 'kategori_barang_id', 'harga_beli', 'harga_jual', 'jenis_satuan', 'stok_awal', 'stok_akhir',];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getLastKodeBarang()
    {
        return $this->select('kode_barang')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getJenisBarang()
    {
        $db = \Config\Database::connect();
        $query = $db->table('satuan_barang');
        $result = $query->get()->getResultArray();

        $jenisBarang = [];
        foreach ($result as $row) {
            $jenisBarang[] = [
                'id' => $row['id'],
                'nama_satuan' => $row['nama_satuan'],
            ];
        }

        return $jenisBarang;
    }

    public function getJenisKategori()
    {
        $db = \Config\Database::connect();
        $query = $db->table('kategori_barang');
        $result = $query->get()->getResultArray();

        $kategoriBarang = [];
        foreach ($result as $row) {
            $kategoriBarang[] = [
                'id' => $row['id'],
                'nama_kategori' => $row['nama_kategori'],
            ];
        }

        return $kategoriBarang;
    }

    // Method untuk mendapatkan detail berdasarkan ID
    public function getDetailById($id)
    {
        return $this->where('id', $id)->first();
    }
}
