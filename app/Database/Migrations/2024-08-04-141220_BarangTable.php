<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BarangTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'harga_beli' => [
                'type'       => 'INTEGER',
                'constraint' => '255',
            ],
            'harga_jual' => [
                'type'       => 'INTEGER',
                'constraint' => '255',
            ],
            'jenis_satuan' => [
                'type' => 'INTEGER',
                'constraint' => '255',
            ],
            'stok_awal' => [
                'type' => 'INTEGER',
                'constraint' => '255',
            ],
            'stok_akhir' => [
                'type' => 'INTEGER',
                'constraint' => '255',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('barang');
    }

    public function down()
    {
        $this->forge->dropTable('barang');
    }
}
