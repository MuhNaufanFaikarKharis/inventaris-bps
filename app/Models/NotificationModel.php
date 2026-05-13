<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // Sesuaikan dengan kolom di database kamu
    protected $allowedFields    = [
        'user_id',
        'title',
        'message',
        'is_read',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false; // Karena kamu sudah pakai default current_timestamp() di DB
}
