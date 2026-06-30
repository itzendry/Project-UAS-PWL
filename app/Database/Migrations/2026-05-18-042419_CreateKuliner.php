<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kuliner extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'nama_tempat' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

            'alamat' => [
                'type' => 'TEXT',
            ],

            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'review' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'rating' => [
                'type' => 'INT',
                'constraint' => 1,
                'default' => 5,
            ],

            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],

            'foto' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'null'       => true, // Wajib true agar boleh kosong saat user tidak upload foto
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('kuliner');
    }

    public function down()
    {
        $this->forge->dropTable('kuliner', true);
    }
}