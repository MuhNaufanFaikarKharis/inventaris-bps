<?php

namespace App\Models;

use CodeIgniter\Model;

class StockOpnameModel extends Model
{
    protected $table            = 'stock_opname'; // Nama tabel di database kamu
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Sesuaikan field ini dengan struktur tabel di database (lihat screenshot kamu)
    protected $allowedFields    = [
        'inventory_id', 
        'stok_sistem', 
        'stok_fisik', 
        'selisih', 
        'keterangan', 
        'created_at'
    ];

    // Aktifkan timestamp jika kamu ingin CI4 mengisi created_at secara otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Kosongkan karena di tabel tidak ada updated_at
}