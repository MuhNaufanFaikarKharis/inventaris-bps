<?php

namespace App\Controllers;

use App\Models\UserModel;

class LoginController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (session()->get('login')) return $this->_redirectByRole();
        return view('auth/login');
    }

    public function process()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        // 1. Cek User Ada atau Tidak
        if (!$user) {
            return redirect()->to(base_url('login'))->with('error', 'Login Gagal: Email tidak ditemukan!');
        }

        // 2. Cek Password
        if (!password_verify($password, $user['password'])) {
            return redirect()->to(base_url('login'))->with('error', 'Login Gagal: Password salah!');
        }

        // 3. Cek Status (Harus Active)
        $status = isset($user['status']) ? strtolower(trim($user['status'])) : '';
        if ($status === 'pending' || $status === '') {
            // Gunakan base_url agar tidak mungkin nyasar ke dashboard umum
            return redirect()->to(base_url('login'))->with('error', 'Login Gagal: Akun Anda belum di-ACC oleh Superadmin.');
        }

        // 4. Lolos Semua, Masuk Session!
        session()->set([
            'user_id'       => $user['id'],
            'nama'          => $user['username'],
            'role'          => strtolower(trim($user['role'])),
            'login'         => true,
            'last_activity' => time()
        ]);

        return $this->_redirectByRole();
    }

    private function _redirectByRole()
    {
        $role = session()->get('role');

        if ($role === 'super admin') {
            return redirect()->to(base_url('superadmin/dashboard'));
        }
        if ($role === 'super visor') {
            return redirect()->to(base_url('supervisor/dashboard'));
        }
        if ($role === 'user' || $role === 'staff') {
            return redirect()->to(base_url('staff/dashboard'));
        }

        // Jika error role, paksa logout!
        return redirect()->to(base_url('logout'));
    }

    public function forgot()
    {
        return view('auth/forgot');
    }

    public function forgotProcess()
    {
        $email = $this->request->getPost('email');
        $answer = strtolower(trim($this->request->getPost('answer')));
        $newPass = $this->request->getPost('new_password');

        $user = $this->userModel->where([
            'email' => $email,
            'security_answer' => $answer
        ])->first();

        if ($user) {
            $this->userModel->update($user['id'], [
                'password' => password_hash($newPass, PASSWORD_DEFAULT)
            ]);
            return redirect()->to('/login')->with('success', 'Password berhasil direset! Silakan login.');
        } else {
            return redirect()->back()->with('error', 'Email atau Jawaban Keamanan tidak cocok!');
        }
    }

    public function logout()
    {
        $session = session();

        if (session_status() === PHP_SESSION_ACTIVE) {
            $session->destroy();
        }

        return redirect()->to('/')->with('success', 'Sesi telah berakhir.');
    }
}
