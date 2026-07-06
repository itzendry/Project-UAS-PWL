<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KulinerSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('favorites')->emptyTable();
$this->db->table('kuliner')->emptyTable();

        $places = [
            ['Warteg Bu Sari', 1, 'Jl. Nakula I No. 5, Semarang', 'Menu rumahan murah untuk mahasiswa.', -6.982050, 110.409140],
            ['Kafe Tembalang Corner', 2, 'Jl. Imam Bonjol No. 207, Semarang', 'Kafe nyaman untuk nugas dan diskusi.', -6.982520, 110.410020],
            ['Sate Ayam Pak Min', 3, 'Jl. Pendrikan Kidul, Semarang', 'Sate ayam kaki lima dengan bumbu kacang kental.', -6.981720, 110.407910],
            ['Es Teh Nusantara Kampus', 4, 'Jl. Nakula Raya, Semarang', 'Minuman segar dekat area kampus.', -6.983100, 110.409800],
            ['Bakso Pak Kumis', 5, 'Jl. Arjuna, Semarang', 'Bakso kuah gurih dengan pilihan mie dan tetelan.', -6.980940, 110.408610],
            ['Seafood Lamongan UDINUS', 6, 'Jl. Bima Raya, Semarang', 'Seafood malam dengan saus padang dan rica.', -6.984020, 110.410830],
            ['Nasi Goreng Mas Roni', 3, 'Jl. Sadewa, Semarang', 'Nasi goreng porsi besar favorit anak kos.', -6.981310, 110.411250],
            ['Kopi Pagi Nakula', 2, 'Jl. Nakula I, Semarang', 'Kopi susu dan roti bakar untuk sarapan.', -6.982830, 110.408940],
            ['Warteg Bahari Imam Bonjol', 1, 'Jl. Imam Bonjol, Semarang', 'Pilihan lauk lengkap dengan harga hemat.', -6.983440, 110.407520],
            ['Tahu Gimbal Kampus', 3, 'Jl. Pindrikan Lor, Semarang', 'Tahu gimbal khas Semarang dekat kampus.', -6.980670, 110.410760],
            ['Jus Buah Sehat', 4, 'Jl. Brotojoyo, Semarang', 'Jus buah segar dengan banyak varian.', -6.979910, 110.409330],
            ['Bakso Mercon Nakula', 5, 'Jl. Nakula Raya, Semarang', 'Bakso pedas dengan isian cabai.', -6.984370, 110.409510],
            ['Ayam Geprek Dewa', 3, 'Jl. Dewa Ruci, Semarang', 'Ayam geprek sambal bawang level pedas.', -6.985020, 110.408230],
            ['Kedai Susu Sore', 4, 'Jl. Bima, Semarang', 'Susu segar, pisang bakar, dan camilan.', -6.982140, 110.412030],
            ['Cafe Diskusi', 2, 'Jl. Hasanudin, Semarang', 'Ruang kecil dengan WiFi dan colokan.', -6.978940, 110.411420],
            ['Pecel Lele Mas Bayu', 3, 'Jl. Pusponjolo, Semarang', 'Lalapan malam murah dan cepat.', -6.986120, 110.407830],
            ['Warteg Kampus Murah', 1, 'Jl. Sadewa Raya, Semarang', 'Paket nasi sayur dan lauk untuk makan siang.', -6.980230, 110.406950],
            ['Kedai Ramen Mahasiswa', 2, 'Jl. Imam Bonjol, Semarang', 'Ramen lokal dengan harga mahasiswa.', -6.984710, 110.411080],
            ['Siomay Bandung Pak Eko', 3, 'Jl. Nakula II, Semarang', 'Siomay, batagor, dan bumbu kacang.', -6.981840, 110.408260],
            ['Es Campur Bu Narti', 4, 'Jl. Arjuna Raya, Semarang', 'Es campur dan es buah porsi besar.', -6.979550, 110.407680],
            ['Seafood Tenda Biru', 6, 'Jl. Kokrosono, Semarang', 'Ikan bakar dan cumi saus tiram.', -6.977860, 110.408970],
        ];

        $data = [];

        foreach ($places as $index => $place) {
            $rating = 3 + ($index % 3);

            $data[] = [
                'user_id'      => $index % 2 === 0 ? 1 : 2,
                'category_id'  => $place[1],
                'nama_tempat'  => $place[0],
                'alamat'       => $place[2],
                'deskripsi'    => $place[3],
                'review'       => 'Rekomendasi komunitas, cocok untuk dicoba saat berada di sekitar kampus.',
                'rating'       => $rating,
                'status'       => 'approved',
                'foto'         => 'default.png',
                'latitude'     => $place[4],
                'longitude'    => $place[5],
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('kuliner')->insertBatch($data);

        echo "Seeder berhasil dijalankan! " . count($data) . " data berhasil ditambahkan.\n";
    }
}