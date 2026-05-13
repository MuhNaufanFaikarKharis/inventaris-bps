<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_kategori', 'deskripsi'];
    protected $useTimestamps    = false; // Karena di SQL kita pakai current_timestamp()
}