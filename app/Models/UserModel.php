<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    // Tambahkan 'status' di baris paling bawah
    protected $allowedFields = [
        'username',
        'email',
        'password',
        'nama_lengkap',
        'role',
        'security_answer',
        'status' // <--- WAJIB DITAMBAHKAN
    ];
}