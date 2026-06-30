<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KulinerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'     => 1, // Diisi ID Admin/User pembawa data awal
                'category_id' => 1, // ID Kategori (misal: Cafe)
                'nama_tempat' => 'Mie Gacoan Babarsari',
                'alamat'      => 'Jl. Babarsari No. 12',
                'deskripsi'   => 'Tempat makan mie pedas favorit mahasiswa dengan harga terjangkau.',
                'review'      => 'Harganya murah meriah, tapi antrenya lumayan panjang. Enak banget buat nongkrong sore.',
                'rating'      => 5,
                'status'      => 'approved',
                'foto'        => 'default.png'
            ],
            [
                'user_id'     => 2, 
                'category_id' => 2, // ID Kategori (misal: Warteg)
                'nama_tempat' => 'Kopi Kenangan',
                'alamat'      => 'Jl. Seturan Raya',
                'deskripsi'   => 'Tempat ngopi asik buat nugas dengan fasilitas wifi kencang.',
                'review'      => 'Kopinya mantap, colokan banyak, wifi kencang.',
                'rating'      => 4,
                'status'      => 'approved',
                'foto'        => 'default.png'
            ],
            [
                'user_id'     => 1,
                'category_id' => 1,
                'nama_tempat' => 'dp mall',
                'alamat'      => 'polion',
                'deskripsi'   => 'Tempat nongkrong anak kos.',
                'review'      => 'enak bet bangke',
                'rating'      => 5,
                'status'      => 'approved',
                'foto'        => 'default.png'
            ]
        ];

        $this->db->table('kuliner')->insertBatch($data);
    }
}