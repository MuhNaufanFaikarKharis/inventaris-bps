<?php

namespace App\Controllers;

use App\Models\UserModel;

class RegisterController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('auth/register');
    }

    public function process()
    {
        // ... (Jika ada validasi form tambahan, taruh di sini) ...

        $data = [
            'nama_lengkap'    => $this->request->getPost('nama_lengkap'),
            'email'           => $this->request->getPost('email'),
            'username'        => $this->request->getPost('username'),
            'password'        => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'security_answer' => strtolower(trim($this->request->getPost('security_answer'))),
            
            // Set default saat registrasi
            'role'            => 'unassigned', // Belum punya role
            'status'          => 'pending',    // Masih menunggu validasi Superadmin
        ];

        $this->userModel->save($data);

        // Setelah sukses simpan ke DB, arahkan ke halaman menunggu konfirmasi
        return redirect()->to('/menunggu-konfirmasi');
    }

    // Method untuk menampilkan view "Menunggu Konfirmasi"
    public function waitingConfirmation()
    {
        return view('auth/waiting_confirmation');
    }
}