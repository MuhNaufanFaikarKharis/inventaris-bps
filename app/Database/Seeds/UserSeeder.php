<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin_dimas',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'nama_lengkap' => 'Dimas (Admin)',
                'role' => 'admin'
            ],
            [
                'username' => 'kepala_bps',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'nama_lengkap' => 'Kepala Kantor',
                'role' => 'kepala'
            ],
            [
                'username' => 'staff_user',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'nama_lengkap' => 'Staff Lapangan',
                'role' => 'staff'
            ],
            [
                'username' => 'restocker_user',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'nama_lengkap' => 'Petugas Gudang',
                'role' => 'restocker'
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}