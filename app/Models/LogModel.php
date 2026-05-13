<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = 'transaction_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['inventory_id', 'tipe', 'qty', 'user_id', 'nama_pelaku', 'keterangan'];
}
