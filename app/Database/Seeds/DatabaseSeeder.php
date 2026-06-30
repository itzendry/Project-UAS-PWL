<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $dataCategory = [

            [
                'nama_kategori' => 'Cafe'
            ],

            [
                'nama_kategori' => 'Warteg'
            ],

            [
                'nama_kategori' => 'Street Food'
            ]

        ];

        $this->db->table('categories')->insertBatch($dataCategory);
        $this->call('UsersSeeder');
        $this->call('CategoriesSeeder');
        $this->call('KulinerSeeder');
        $this->call('ReviewsSeeder');
    }
}