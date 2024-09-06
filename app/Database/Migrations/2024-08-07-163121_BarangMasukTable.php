<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BarangMasuk extends Migration
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
            'kode_transaksi' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'kode_barang' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'jumlah_masuk' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'stok_saat_ini' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'tanggal_masuk' => [
                'type' => 'DATE',
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
        $this->forge->createTable('barang_masuk');
    }

    public function down()
    {
        $this->forge->dropTable('barang_masuk');
    }
}
