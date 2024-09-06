<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'password_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'role_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive', 'Banned'],
                'default' => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('user_id', true); // Primary key
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
