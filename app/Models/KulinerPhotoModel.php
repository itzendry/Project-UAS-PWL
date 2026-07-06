<?php

namespace App\Models;

use CodeIgniter\Model;

class KulinerPhotoModel extends Model
{
    protected $table = 'kuliner_photos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kuliner_id',
        'foto',
    ];

    protected $useTimestamps = true;
}
