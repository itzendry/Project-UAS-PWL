<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'kuliner_id',
        'review',
        'rating',
    ];

    protected $useTimestamps = true;
}
