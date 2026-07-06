<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        for ($kulinerId = 1; $kulinerId <= 21; $kulinerId++) {
            $rating = 3 + ($kulinerId % 3);
            $data[] = [
                'user_id' => $kulinerId % 2 === 0 ? 1 : 2,
                'kuliner_id' => $kulinerId,
                'review' => 'Tempatnya layak dicoba, harga masih ramah mahasiswa.',
                'rating' => $rating,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('reviews')->insertBatch($data);
    }
}
