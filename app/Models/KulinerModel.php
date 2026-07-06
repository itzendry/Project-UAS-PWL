<?php

namespace App\Models;

use CodeIgniter\Model;

class KulinerModel extends Model
{
    protected $table = 'kuliner';
    protected $primaryKey = 'id';

    protected $allowedFields = [
    'user_id',
    'category_id',
    'nama_tempat',
    'alamat',
    'deskripsi',
    'review',
    'rating',
    'status',
    'foto',
    'latitude',
    'longitude'
];

    protected $useTimestamps = true;
}
