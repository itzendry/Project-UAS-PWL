<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'nama' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status'     => 'active'
            ],

            [
                'nama' => 'Budi Santoso',
                'email' => 'user@gmail.com',
                'password' => password_hash('user12345', PASSWORD_DEFAULT),
                'role' => 'user',
                'status'     => 'active'
            ],

        ];

        $this->db->table('users')->insertBatch($data);
    }
}