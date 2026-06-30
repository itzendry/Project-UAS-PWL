<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $data = [

            ['nama_kategori' => 'Warteg'],
            ['nama_kategori' => 'Cafe'],
            ['nama_kategori' => 'Street Food'],
            ['nama_kategori' => 'Minuman'],
            ['nama_kategori' => 'Bakso'],
            ['nama_kategori' => 'Seafood'],

        ];

        $this->db->table('categories')->insertBatch($data);
    }
}