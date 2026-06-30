<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoriteModel extends Model
{
    protected $table            = 'favorites';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['user_id', 'kuliner_id'];
    
    // Aktifkan timestamp karena di migration kamu ada created_at
    protected $useTimestamps    = true; 
    protected $updatedField     = ''; // Kosongkan karena di tabel favorites tidak ada updated_at (biasanya)
}