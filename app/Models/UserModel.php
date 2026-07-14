<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    // TAMBAHKAN 'status' DAN 'token' DI SINI
    protected $allowedFields = [
        'nama',
        'email',
        'password',
        'role',
        'status',
        'token'
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';
}