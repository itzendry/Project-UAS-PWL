<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'user_id' => 2,
                'kuliner_id' => 1,
                'review' => 'Makanan enak dan murah',
                'rating' => 5,
            ],

            [
                'user_id' => 1,
                'kuliner_id' => 2,
                'review' => 'Tempat nyaman untuk nongkrong',
                'rating' => 4,
            ]

        ];

        $this->db->table('reviews')->insertBatch($data);
    }
}