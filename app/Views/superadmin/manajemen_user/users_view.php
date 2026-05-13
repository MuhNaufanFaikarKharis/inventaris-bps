<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-6 lg:space-y-10 pb-10">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tighter italic uppercase leading-none text-center lg:text-left">
                Manajemen <span class="text-blue-600">Pengguna</span>
            </h3>
            <div class="flex items-center justify-center lg:justify-start gap-3 mt-2">
                <span class="px-2.5 py-0.5 bg-slate-800 text-white text-[9px] font-black rounded-lg uppercase tracking-widest shadow-sm">
                    Access Control
                </span>
                <p class="text-xs lg:text-sm text-slate-500 font-medium italic">
                    Kelola kredensial, validasi akun, dan hak akses pegawai.
                </p>
            </div>
        </div>

        <button onclick="openUserModal()" class="group flex items-center justify-center gap-3 px-8 py-4 bg-blue-600 rounded-[25px] text-[11px] font-black text-white hover:bg-slate-900 transition-all uppercase tracking-[0.2em] shadow-xl shadow-blue-200 w-full lg:w-auto">
            <i class="fas fa-user-plus group-hover:scale-125 transition-transform"></i> Tambah User Baru
        </button>
    </div>

    <div class="bg-white rounded-3xl lg:rounded-[50px] border border-slate-200 overflow-hidden shadow-xl shadow-slate-200/40">
        <div class="p-6 lg:p-10 border-b border-slate-100 flex items-center gap-4 bg-slate-50/50">
            <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center shadow-lg"><i class="fas fa-users-cog text-xs"></i></div>
            <div>
                <h4 class="font-black text-slate-800 uppercase italic tracking-tighter">Database Kredensial</h4>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Sistem Akun & Validasi</p>
            </div>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left min-w-[900px]">
                <thead class="bg-slate-50/80 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b">
                    <tr>
                        <th class="px-6 lg:px-10 py-6">Profil Pegawai</th>
                        <th class="px-6 lg:px-10 py-6">Kontak & Kredensial</th>
                        <th class="px-6 lg:px-10 py-6 text-center">Status & Role</th>
                        <th class="px-6 lg:px-10 py-6 text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <!-- Baris tabel diberi highlight kuning halus jika statusnya masih pending -->
                            <tr class="transition-all group <?= (isset($u['status']) && $u['status'] === 'pending') ? 'bg-amber-50/30' : 'hover:bg-slate-50' ?>">
                                <td class="px-6 lg:px-10 py-6 lg:py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center font-black text-slate-400 group-hover:from-blue-600 group-hover:to-indigo-600 group-hover:text-white transition-all shadow-inner uppercase italic text-sm lg:text-base">
                                            <?= substr($u['nama_lengkap'] ?? 'U', 0, 1) ?>
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-700 uppercase italic leading-none mb-1 text-sm lg:text-base"><?= esc($u['nama_lengkap'] ?? 'No Name') ?></p>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest italic">User ID: #<?= esc($u['id']) ?></p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 lg:px-10 py-6 lg:py-8">
                                    <p class="font-bold text-slate-600 text-xs lg:text-sm mb-1"><?= esc($u['email'] ?? '-') ?></p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">@<?= esc($u['username'] ?? '-') ?></p>
                                </td>

                                <td class="px-6 lg:px-10 py-6 lg:py-8 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <!-- INDIKATOR STATUS -->
                                        <?php if (isset($u['status']) && $u['status'] === 'pending'): ?>
                                            <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-lg text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                <i class="fas fa-clock"></i> Pending Validasi
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        <?php endif; ?>

                                        <!-- INDIKATOR ROLE -->
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest italic">
                                            Role: <?= esc(strtoupper($u['role'] ?? 'UNASSIGNED')) ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 lg:px-10 py-6 lg:py-8 text-right">
                                    <div class="flex justify-end gap-2 lg:gap-3">
                                        <!-- Tombol Edit/Validasi: Berubah warna jika butuh divalidasi -->
                                        <button onclick='editUser(<?= json_encode($u) ?>)' class="w-10 h-10 rounded-xl transition-all flex items-center justify-center shrink-0 <?= (isset($u['status']) && $u['status'] === 'pending') ? 'bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white shadow-lg shadow-amber-200' : 'bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white' ?>" title="Edit / Validasi Akun">
                                            <i class="<?= (isset($u['status']) && $u['status'] === 'pending') ? 'fas fa-user-check' : 'fas fa-edit' ?> text-xs"></i>
                                        </button>

                                        <button onclick="confirmDelete(<?= $u['id'] ?>)" class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shrink-0">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-10 py-24 text-center text-slate-300 font-black italic tracking-widest uppercase text-xs">Database User Kosong</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH/EDIT/VALIDASI USER -->
<div id="modalUser" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4 transition-all">
    <div class="bg-white rounded-3xl lg:rounded-[50px] w-full max-w-2xl p-6 lg:p-10 shadow-2xl fade-in border border-slate-100 relative max-h-[95vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-lg"><i class="fas fa-user-shield text-xs"></i></div>
                <div>
                    <h3 id="modalTitle" class="text-lg lg:text-xl font-black text-slate-800 uppercase italic tracking-tighter">User Form</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Kredensial Otorisasi & Akses</p>
                </div>
            </div>
            <button onclick="closeUserModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 transition-colors"><i class="fas fa-times"></i></button>
        </div>

        <form action="<?= base_url('superadmin/users/save') ?>" method="POST" class="space-y-4 lg:space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="user_id">

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="user_nama" required class="w-full bg-slate-50 border-none rounded-xl lg:rounded-[20px] px-6 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner transition-all" placeholder="Input Nama Pegawai...">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Username Sistem</label>
                    <input type="text" name="username" id="user_username" required class="w-full bg-slate-50 border-none rounded-xl lg:rounded-[20px] px-6 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner transition-all" placeholder="Username...">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Email Resmi</label>
                    <input type="email" name="email" id="user_email" required class="w-full bg-slate-50 border-none rounded-xl lg:rounded-[20px] px-6 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner transition-all" placeholder="Email...">
                </div>
            </div>

            <!-- BAGIAN BARU: PEMILIHAN ROLE & STATUS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest ml-2">Hak Akses (Role)</label>
                    <select name="role" id="user_role" required class="w-full bg-white border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm cursor-pointer">
                        <option value="user">User / Staff</option>
                        <option value="super visor">Supervisor</option>
                        <option value="super admin">Superadmin</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest ml-2">Status Akun</label>
                    <select name="status" id="user_status" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm cursor-pointer appearance-none">
                        <option value="" disabled selected>-- Pilih Status --</option>
                        <option value="Pending">Pending (Menunggu Validasi)</option>
                        <option value="Active">Active (Diizinkan Login)</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Key Authorization (Password)</label>
                <input type="password" name="password" id="user_password" class="w-full bg-slate-50 border-none rounded-xl lg:rounded-[20px] px-6 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner transition-all" placeholder="Ketik jika ingin mengubah password...">
                <p id="passWarning" class="text-[9px] text-rose-400 italic ml-4 hidden font-bold">*Abaikan / biarkan kosong jika tidak ingin merubah password saat ini.</p>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="button" onclick="closeUserModal()" class="flex-1 py-4 lg:py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Batal</button>
                <button type="submit" class="flex-[2] bg-slate-900 text-white py-4 lg:py-5 rounded-xl lg:rounded-[25px] font-black text-[10px] uppercase tracking-[0.2em] shadow-xl hover:bg-blue-600 transition-all active:scale-95">
                    Simpan & Validasi <i class="fas fa-check-circle ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openUserModal() {
        document.getElementById('modalTitle').innerText = 'Daftarkan Akun Baru';
        document.getElementById('user_id').value = '';
        document.getElementById('user_nama').value = '';
        document.getElementById('user_username').value = '';
        document.getElementById('user_email').value = '';

        // Reset pilihan role dan status default untuk user baru
        document.getElementById('user_role').value = 'user';
        document.getElementById('user_status').value = 'active';

        document.getElementById('user_password').required = true;
        document.getElementById('passWarning').classList.add('hidden');
        document.getElementById('modalUser').classList.remove('hidden');
    }

    function editUser(data) {
        // 1. Logika Judul Modal
        if (data.status === 'Pending' || data.status === 'pending') {
            document.getElementById('modalTitle').innerText = 'Validasi Akun Baru';
        } else {
            document.getElementById('modalTitle').innerText = 'Otorisasi Edit User';
        }

        // 2. Isi data input biasa
        document.getElementById('user_id').value = data.id || '';
        document.getElementById('user_nama').value = data.nama_lengkap || data.nama || '';
        document.getElementById('user_username').value = data.username || '';
        document.getElementById('user_email').value = data.email || '';

        // 3. SET DROPDOWN ROLE (Pastikan value sesuai: 'user', 'super admin', 'super visor')
        document.getElementById('user_role').value = data.role;

        // 4. SET DROPDOWN STATUS (PENTING!)
        // Kita buat fleksibel agar bisa membaca 'Pending' maupun 'pending'
        let statusDb = data.status;
        if (statusDb === 'pending') statusDb = 'Pending';
        if (statusDb === 'active') statusDb = 'Active';

        document.getElementById('user_status').value = statusDb;

        // 5. Password logic
        document.getElementById('user_password').required = false;
        document.getElementById('user_password').value = '';
        document.getElementById('passWarning').classList.remove('hidden');

        // Tampilkan Modal
        document.getElementById('modalUser').classList.remove('hidden');
    }

    function closeUserModal() {
        document.getElementById('modalUser').classList.add('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'OTORITAS PENGHAPUSAN',
            text: "Kredensial akses ini akan dicabut secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            confirmButtonText: 'KONFIRMASI HAPUS',
            cancelButtonText: 'BATAL',
            customClass: {
                popup: 'rounded-[30px] lg:rounded-[40px]',
                confirmButton: 'rounded-xl font-bold px-8 py-3 uppercase tracking-widest text-[10px]',
                cancelButton: 'rounded-xl font-bold px-8 py-3 uppercase tracking-widest text-[10px]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('superadmin/users/delete') ?>/" + id;
            }
        });
    }

    window.onclick = function(e) {
        if (e.target == document.getElementById('modalUser')) closeUserModal();
    }
</script>
<?= $this->endSection() ?>