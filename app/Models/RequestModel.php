<?php

namespace App\Models;

use CodeIgniter\Model;

class RequestModel extends Model
{
    // Nama tabel sesuai database yang baru kita buat
    protected $table            = 'requests';
    
    // Primary key tabel
    protected $primaryKey       = 'id';

    // Menggunakan auto increment untuk ID
    protected $useAutoIncrement = true;

    // Tipe data yang dikembalikan saat memanggil data
    protected $returnType       = 'array';

    // Proteksi Field (Wajib diisi sesuai kolom di database)
    protected $allowedFields    = [
        'nama_barang', 
        'qty', 
        'alasan', 
        'user_id',      // Menghubungkan ke ID User di tabel users
        'nama_pemohon', 
        'status',      // pending, disetujui, ditolak
        'bukti_foto'
    ];

    // Fitur Timestamp Otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Fungsi opsional untuk mengambil data request 
     * beserta informasi usernya (Join ke tabel users)
     */
    public function getRequestsWithUser()
    {
        return $this->select('requests.*, users.nama_lengkap as nama_user, users.role')
                    ->join('users', 'users.id = requests.user_id')
                    ->orderBy('requests.created_at', 'DESC')
                    ->findAll();
    }
}