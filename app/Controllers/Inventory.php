<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\LogModel;
use App\Models\RequestModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\StockOpnameModel;

class Inventory extends BaseController
{
    protected $invModel;
    protected $logModel;
    protected $requestModel;
    protected $userModel;
    protected $categoryModel;
    protected $opnameModel;

    public function __construct()
    {
        $this->invModel     = new InventoryModel();
        $this->logModel     = new LogModel();
        $this->requestModel = new RequestModel();
        $this->userModel    = new UserModel();
        $this->categoryModel = new CategoryModel();
        $this->opnameModel   = new StockOpnameModel();
    }

    public function index()
    {
        $role   = session()->get('role');
        $userId = session()->get('user_id');

        $data = [
            'inventory' => $this->invModel->select('inventory.*, categories.nama_kategori')
                ->join('categories', 'categories.id = inventory.category_id', 'left')
                ->findAll(),
            'role'      => $role,
            'title'     => 'Monitoring Stok BPS'
        ];

        if (session()->get('login')) {

            // --- LOGIKA DATA GRAFIK (REAL TIME 7 HARI TERAKHIR) ---
            // Kita siapkan data ini hanya untuk role yang butuh grafik (Admin & Supervisor)
            if (in_array($role, ['super admin', 'super visor'])) {
                $db = \Config\Database::connect();
                $chartData = [
                    'labels' => [],
                    'masuk'  => [],
                    'keluar' => []
                ];

                for ($i = 6; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $dayName = date('D', strtotime($date)); // Nama hari singkat (Mon, Tue, dst)

                    // Hitung total QTY Masuk di tanggal tersebut
                    $masuk = $db->table('transaction_logs')
                        ->where('tipe', 'Masuk')
                        ->where("DATE(created_at)", $date)
                        ->selectSum('qty')->get()->getRow()->qty ?? 0;

                    // Hitung total QTY Keluar di tanggal tersebut
                    $keluar = $db->table('transaction_logs')
                        ->where('tipe', 'Keluar')
                        ->where("DATE(created_at)", $date)
                        ->selectSum('qty')->get()->getRow()->qty ?? 0;

                    $chartData['labels'][] = $dayName;
                    $chartData['masuk'][]  = (int)$masuk;
                    $chartData['keluar'][] = (int)$keluar;
                }
                $data['chart'] = $chartData;
            }
            // --- END LOGIKA GRAFIK ---

            if ($role === 'super admin') {
                $data['title']    = 'Panel Utama Super Admin';
                $data['requests'] = $this->requestModel->orderBy('created_at', 'DESC')->findAll();
                $data['logs']     = $this->logModel->select('transaction_logs.*, inventory.nama_barang')
                    ->join('inventory', 'inventory.id = transaction_logs.inventory_id')
                    ->orderBy('created_at', 'DESC')->findAll();
                return view('superadmin/dashboard', $data);
            }

            if ($role === 'super visor') {
                $data['title'] = 'Panel Monitoring Supervisor';
                $data['logs']  = $this->logModel->select('transaction_logs.*, inventory.nama_barang')
                    ->join('inventory', 'inventory.id = transaction_logs.inventory_id')
                    ->orderBy('created_at', 'DESC')->findAll();
                return view('supervisor/dashboard', $data);
            }

            if ($role === 'user' || $role === 'staff') {
                $data['title']    = 'Dashboard Pegawai';
                $data['requests'] = $this->requestModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();
                return view('staff/dashboard', $data);
            }
        }

        return view('inventory_view', $data);
    }

    /**
     * Simpan Penambahan Stok (Hanya bagian yang diperbaiki)
     */
    public function save_tambah()
    {
        if (session()->get('role') !== 'super admin') {
            return redirect()->back()->with('error', 'Hanya Super Admin yang dapat menambah stok!');
        }

        // 1. Tangkap data dari Post View
        $nama       = $this->request->getPost('nama');
        $qty        = $this->request->getPost('qty');
        $categoryId = $this->request->getPost('category_id');
        $keterangan = $this->request->getPost('keterangan') ?: 'Penambahan stok inventaris baru';

        // SET SATUAN OTOMATIS KE PACK
        $satuan     = 'Pack';

        // 2. Cek apakah barang sudah ada
        $item = $this->invModel->where('nama_barang', $nama)->first();

        if ($item) {
            // JIKA ADA: Update stok, kategori, dan pastikan satuan tetap 'Pack'
            $this->invModel->update($item['id'], [
                'stok'        => $item['stok'] + $qty,
                'category_id' => $categoryId,
                'satuan'      => $satuan // Tambahkan ini
            ]);
            $invId = $item['id'];
        } else {
            // JIKA BARU: Masukkan data baru termasuk category_id dan satuan 'Pack'
            $invId = $this->invModel->insert([
                'nama_barang' => $nama,
                'category_id' => $categoryId,
                'stok'        => $qty,
                'satuan'      => $satuan // Tambahkan ini
            ]);
        }

        // 3. Catat Log Transaksi
        $this->logModel->insert([
            'inventory_id' => $invId,
            'tipe'         => 'Masuk',
            'qty'          => $qty,
            'user_id'      => session()->get('user_id'),
            'nama_pelaku'  => session()->get('nama'),
            'keterangan'   => $keterangan
        ]);

        // 4. Kirim Notifikasi (Tambahkan kata 'Pack' di pesan agar lebih jelas)
        $db = \Config\Database::connect();
        $db->table('notifications')->insert([
            'user_id'    => session()->get('user_id'),
            'title'      => 'MASUK: Restock Berhasil',
            'message'    => "Stok $nama telah berhasil ditambah sebanyak $qty $satuan.",
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/superadmin/dashboard')->with('success', 'Stok berhasil diperbarui!');
    }

    public function update_status($id, $status)
    {
        $role = session()->get('role');
        if (!in_array($role, ['super admin', 'super visor'])) return redirect()->back();

        $request = $this->requestModel->find($id);
        if (!$request) return redirect()->back()->with('error', 'Data permintaan tidak ditemukan.');

        if ($status === 'disetujui') {
            $barang = $this->invModel->where('nama_barang', $request['nama_barang'])->first();

            if (!$barang || $barang['stok'] < $request['qty']) {
                return redirect()->back()->with('error', 'Gagal setuju! Stok di gudang tidak mencukupi.');
            }

            $this->invModel->update($barang['id'], ['stok' => $barang['stok'] - $request['qty']]);

            $this->logModel->insert([
                'inventory_id' => $barang['id'],
                'tipe'         => 'Keluar',
                'qty'          => $request['qty'],
                'user_id'      => $request['user_id'],
                'nama_pelaku'  => $request['nama_pemohon'],
                'keterangan'   => 'Permintaan disetujui: ' . $request['alasan']
            ]);

            // NOTIFIKASI KELUAR UNTUK STAFF (PEMOHON)
            $db = \Config\Database::connect();
            $db->table('notifications')->insert([
                'user_id'    => $request['user_id'],
                'title'      => 'KELUAR: Barang Disetujui',
                'message'    => "Permintaan " . $request['nama_barang'] . " (" . $request['qty'] . " unit) sudah disetujui.",
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->requestModel->update($id, ['status' => $status]);
        return redirect()->back()->with('success', 'Status permintaan berhasil diperbarui!');
    }

    // ... (Sisa fungsi lainnya: stok_management, save_request, dll tetap sama tanpa perubahan)

    public function stok_management()
    {
        $role = session()->get('role');
        if (!session()->get('login')) return redirect()->to('/login');

        $data = [
            'title'     => ($role === 'staff' || $role === 'user') ? 'Katalog Stok ATK' : 'Manajemen Stok ATK',
            'inventory' => $this->invModel->select('inventory.*, categories.nama_kategori')
                ->join('categories', 'categories.id = inventory.category_id', 'left')
                ->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'role'      => $role
        ];

        if ($role === 'user' || $role === 'staff') {
            return view('staff/list_barang/stok_view', $data);
        }

        return view('superadmin/stok/stok_view', $data);
    }

    public function delete_stok($id)
    {
        if (session()->get('role') !== 'super admin') return redirect()->back();

        // 1. Hapus data di tabel-tabel anak terlebih dahulu
        $this->logModel->where('inventory_id', $id)->delete();
        $this->opnameModel->where('inventory_id', $id)->delete();

        // 2. Baru hapus data di tabel utama (inventory)
        if ($this->invModel->delete($id)) {
            return redirect()->to('/superadmin/stok')->with('success', 'Barang dan riwayatnya berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus barang.');
        }
    }

    public function save_request()
    {
        if (!session()->get('login')) return redirect()->to('/login');

        $namaBarang = $this->request->getPost('nama_barang');
        $qty = $this->request->getPost('qty');
        $namaPemohon = session()->get('nama');

        // 1. Simpan ke tabel requests (Data utama)
        $this->requestModel->insert([
            'nama_barang'  => $namaBarang,
            'qty'          => $qty,
            'alasan'       => $this->request->getPost('alasan'),
            'user_id'      => session()->get('user_id'),
            'nama_pemohon' => $namaPemohon,
            'status'       => 'pending'
        ]);

        // 2. KIRIM NOTIFIKASI KE SEMUA ADMIN (Agar tombol surat Admin ada isinya)
        $db = \Config\Database::connect();

        // Ambil daftar semua user yang role-nya super admin
        $admins = $db->table('users')->where('role', 'super admin')->get()->getResult();

        foreach ($admins as $admin) {
            $db->table('notifications')->insert([
                'user_id'    => $admin->id, // ID Admin penerima
                'title'      => 'KELUAR: Permintaan Baru', // Prefix KELUAR agar icon biru muncul
                'message'    => "Ada permintaan baru dari $namaPemohon: $namaBarang ($qty unit).",
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to('/staff/request')->with('success', 'Permintaan Anda telah dikirim.');
    }

    public function validasi_request()
    {
        if (!in_array(session()->get('role'), ['super admin', 'super visor'])) return redirect()->to('/login');
        $data = [
            'title'    => 'Validasi Permintaan ATK',
            'requests' => $this->requestModel->orderBy('created_at', 'DESC')->findAll(),
            'role'     => session()->get('role')
        ];
        return view('superadmin/validasi_view', $data);
    }

    public function laporan_view()
    {
        if (!in_array(session()->get('role'), ['super admin', 'super visor'])) return redirect()->to('/login');

        // Ambil ID dari session login (mencoba beberapa kunci umum jika 'id' kosong)
        $userId = session()->get('id') ?: session()->get('user_id') ?: session()->get('id_user');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);

        // Jika user ditemukan di DB dan field nama_lengkap ada isinya, gunakan itu.
        // Jika tidak, baru gunakan session nama (username).
        $namaLengkap = ($user && !empty($user['nama_lengkap'])) ? $user['nama_lengkap'] : session()->get('nama');

        // Tangkap Filter (Tetap sesuai kode kamu)
        $kategori  = $this->request->getGet('kategori') ?: 'mutasi';
        $tgl_awal  = $this->request->getGet('tgl_awal');
        $tgl_akhir = $this->request->getGet('tgl_akhir');
        $tipe      = $this->request->getGet('tipe');

        $data_laporan = [];

        // Logika Pengambilan Data Berdasarkan Kategori (Tetap sesuai kode kamu)
        if ($kategori === 'mutasi') {
            $builder = $this->logModel->select('transaction_logs.*, inventory.nama_barang')
                ->join('inventory', 'inventory.id = transaction_logs.inventory_id');
            if ($tipe) $builder->where('tipe', $tipe);
            if ($tgl_awal && $tgl_akhir) $builder->where("DATE(transaction_logs.created_at) >=", $tgl_awal)->where("DATE(transaction_logs.created_at) <=", $tgl_akhir);
            $data_laporan = $builder->orderBy('transaction_logs.created_at', 'DESC')->findAll();
        } elseif ($kategori === 'permintaan') {
            $builder = $this->requestModel->select('*');
            if ($tgl_awal && $tgl_akhir) $builder->where("DATE(created_at) >=", $tgl_awal)->where("DATE(created_at) <=", $tgl_akhir);
            $data_laporan = $builder->orderBy('created_at', 'DESC')->findAll();
        } elseif ($kategori === 'audit') {
            $builder = $this->opnameModel->select('stock_opname.*, inventory.nama_barang')
                ->join('inventory', 'inventory.id = stock_opname.inventory_id');
            if ($tgl_awal && $tgl_akhir) $builder->where("DATE(stock_opname.created_at) >=", $tgl_awal)->where("DATE(stock_opname.created_at) <=", $tgl_akhir);
            $data_laporan = $builder->orderBy('stock_opname.created_at', 'DESC')->findAll();
        }

        $data = [
            'title'      => 'Laporan Pusat BPS',
            'role'       => session()->get('role'),
            'kategori'   => $kategori,
            'logs'       => $data_laporan,
            'nama_ttd'   => $namaLengkap, // Variabel dikirim ke view
            'tgl_awal'   => $tgl_awal,
            'tgl_akhir'  => $tgl_akhir,
            'tipe'       => $tipe
        ];

        return view('superadmin/laporan/laporan_view', $data);
    }

    public function user_management()
    {
        if (session()->get('role') !== 'super admin') return redirect()->to('/login');
        $data = [
            'title' => 'Manajemen Pengguna',
            'role'  => session()->get('role'),
            'users' => $this->userModel->findAll()
        ];
        return view('superadmin/manajemen_user/users_view', $data);
    }

    public function user_save()
    {
        if (session()->get('role') !== 'super admin') return redirect()->back();

        $id = $this->request->getPost('id');

        $data = [
            'username'     => $this->request->getPost('username'),

            // PERBAIKAN 1: Tangkap 'nama_lengkap' dari form, bukan 'nama'
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),

            'email'        => $this->request->getPost('email'),
            'role'         => $this->request->getPost('role'),

            // PERBAIKAN 2: Tangkap 'status' agar Superadmin bisa mengubah Pending -> Active
            'status'       => $this->request->getPost('status'),
        ];

        $pass = $this->request->getPost('password');
        if (!empty($pass)) {
            $data['password'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        if ($id) {
            $this->userModel->update($id, $data);
            $pesan = 'Data User berhasil divalidasi dan diperbarui!';
        } else {
            $this->userModel->insert($data);
            $pesan = 'User baru berhasil ditambahkan!';
        }

        return redirect()->to('/superadmin/users')->with('success', $pesan);
    }

    public function user_delete($id)
    {
        if (session()->get('role') !== 'super admin' || $id == session()->get('user_id')) return redirect()->back();
        $this->userModel->delete($id);
        return redirect()->to('/superadmin/users')->with('success', 'User dihapus.');
    }

    public function request_view()
    {
        if (!in_array(session()->get('role'), ['user', 'staff'])) return redirect()->to('/login');
        $data = [
            'title'     => 'Permintaan Barang ATK',
            'inventory' => $this->invModel->where('stok >', 0)->findAll(),
            'requests'  => $this->requestModel->where('user_id', session()->get('user_id'))->orderBy('created_at', 'DESC')->findAll(),
            'role'      => session()->get('role')
        ];
        return view('staff/permintaan/request_view', $data);
    }

    public function delete($id)
    {
        if (session()->get('role') === 'super visor') {
            $this->invModel->delete($id);
            return redirect()->to('/supervisor/dashboard')->with('success', 'Barang dihapus!');
        }
        return redirect()->back();
    }

    public function laporan_staff()
    {
        $role = session()->get('role');
        $nama_user = session()->get('nama');

        if (!in_array($role, ['user', 'staff'])) {
            return redirect()->to('/login');
        }

        // Ambil input filter tanggal
        $tgl_awal  = $this->request->getGet('tgl_awal');
        $tgl_akhir = $this->request->getGet('tgl_akhir');

        $builder = $this->logModel->select('transaction_logs.*, inventory.nama_barang')
            ->join('inventory', 'inventory.id = transaction_logs.inventory_id')
            ->where('transaction_logs.tipe', 'Keluar')
            ->where('transaction_logs.nama_pelaku', $nama_user);

        // Jika ada filter tanggal, tambahkan ke query
        if (!empty($tgl_awal) && !empty($tgl_akhir)) {
            $builder->where("DATE(transaction_logs.created_at) >=", $tgl_awal);
            $builder->where("DATE(transaction_logs.created_at) <=", $tgl_akhir);
        }

        $data = [
            'title'     => 'Laporan Penggunaan ATK Saya',
            'role'      => $role,
            'logs'      => $builder->orderBy('transaction_logs.created_at', 'DESC')->findAll(),
            'tgl_awal'  => $tgl_awal, // kirim balik ke view untuk isi input
            'tgl_akhir' => $tgl_akhir
        ];

        return view('staff/laporan/laporan_view', $data);
    }

    public function profil_user()
    {
        if (!session()->get('login')) return redirect()->to('/login');

        $data = [
            'title' => 'Profil Saya',
            'user'  => $this->userModel->find(session()->get('user_id')),
            'role'  => session()->get('role')
        ];
        return view('user/profil_view', $data);
    }

    public function update_profil()
    {
        if (!session()->get('login')) return redirect()->to('/login');

        $id = session()->get('user_id');
        $nama = $this->request->getPost('nama');
        $pass = $this->request->getPost('password');

        $data = ['nama_lengkap' => $nama];

        if (!empty($pass)) {
            $data['password'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $data)) {
            session()->set('nama', $nama);
            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui profil.');
    }

    public function carousel_view()
    {
        if (session()->get('role') !== 'super admin') return redirect()->to('/login');

        $db = \Config\Database::connect();
        $data = [
            'title'  => 'Manajemen Carousel',
            'role'   => session()->get('role'),
            'slides' => $db->table('carousels')->orderBy('created_at', 'DESC')->get()->getResult()
        ];

        return view('superadmin/carousel/carousel_view', $data);
    }

    public function save_carousel()
    {
        if (session()->get('role') !== 'super admin') return redirect()->back();

        $file = $this->request->getFile('image');
        $title = $this->request->getPost('title');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/carousel', $newName);

            $db = \Config\Database::connect();
            $db->table('carousels')->insert([
                'title'      => $title,
                'image_path' => $newName,
                'is_active'  => 1,
                'user_id'    => session()->get('user_id'),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->back()->with('success', 'Foto carousel berhasil ditambahkan!');
        }
        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }

    public function delete_carousel($id)
    {
        if (session()->get('role') !== 'super admin') return redirect()->back();

        $db = \Config\Database::connect();
        $image = $db->table('carousels')->where('id', $id)->get()->getRow();

        if ($image) {
            $path = 'uploads/carousel/' . $image->image_path;
            if (file_exists($path)) {
                unlink($path);
            }
            $db->table('carousels')->where('id', $id)->delete();
        }
        return redirect()->back()->with('success', 'Foto carousel dihapus.');
    }

    // Tambahkan fungsi ini di dalam class Inventory
    public function konfirmasi_terima($id)
    {
        if (!session()->get('login')) return redirect()->to('/login');

        $file = $this->request->getFile('bukti_foto');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            // Simpan ke folder public/uploads/bukti_terima
            $file->move('uploads/bukti_terima', $newName);

            // Update status di database menjadi 'diterima' atau tetap 'disetujui' dengan bukti
            $this->requestModel->update($id, [
                'bukti_foto' => $newName,
                'status'     => 'disetujui' // atau buat status baru 'selesai' jika perlu
            ]);

            return redirect()->back()->with('success', 'Barang berhasil dikonfirmasi dengan bukti foto!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah foto bukti.');
    }

    public function save_opname()
    {
        // Proteksi Akses
        if (session()->get('role') !== 'super admin') return redirect()->back();

        // 1. Ambil data dari form modal di dashboard
        $invId      = $this->request->getPost('inventory_id');
        $stokFisik  = $this->request->getPost('stok_fisik');
        $keterangan = $this->request->getPost('keterangan');

        // 2. Ambil data barang dari database untuk hitung selisih
        $barang = $this->invModel->find($invId);
        if (!$barang) return redirect()->back()->with('error', 'Barang tidak ditemukan.');

        $stokSistem = $barang['stok'];
        $selisih    = $stokFisik - $stokSistem;

        // 3. Masukkan data ke tabel stock_opname (untuk rekapan audit)
        $this->opnameModel->insert([
            'inventory_id' => $invId,
            'stok_sistem'  => $stokSistem,
            'stok_fisik'   => $stokFisik,
            'selisih'      => $selisih,
            'keterangan'   => $keterangan,
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        // 4. Update stok utama di tabel inventory
        $this->invModel->update($invId, ['stok' => $stokFisik]);

        // 5. Catat ke transaction_logs agar muncul di TIMELINE Dashboard
        $this->logModel->insert([
            'inventory_id' => $invId,
            'tipe'         => ($selisih >= 0) ? 'Masuk' : 'Keluar',
            'qty'          => abs($selisih),
            'user_id'      => session()->get('user_id'),
            'nama_pelaku'  => session()->get('nama'),
            'keterangan'   => "OPNAME: $keterangan (Selisih: $selisih)"
        ]);

        return redirect()->to('/superadmin/dashboard')->with('success', 'Sinkronisasi Stock Opname berhasil!');
    }

    public function riwayat_opname()
    {
        // Cek login & role
        if (!in_array(session()->get('role'), ['super admin', 'super visor'])) return redirect()->to('/login');

        $data = [
            'title'   => 'Riwayat Audit Stock Opname',
            'role'    => session()->get('role'),
            'history' => $this->opnameModel->select('stock_opname.*, inventory.nama_barang')
                ->join('inventory', 'inventory.id = stock_opname.inventory_id')
                ->orderBy('stock_opname.created_at', 'DESC')
                ->findAll()
        ];

        // Pastikan path view ini sesuai dengan folder kamu
        return view('superadmin/riwayat_opname/riwayat_view', $data);
    }

    public function markAllAsRead()
    {
        $db = \Config\Database::connect();
        $db->table('notifications')
            ->where('user_id', session()->get('user_id'))
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return $this->response->setJSON(['status' => 'success']);
    }
}
