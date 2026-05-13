<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPS-INV | <?= strtoupper(session()->get('role') ?? 'UMUM') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        /* Smooth Gradient Background Animation */
        @keyframes gradient-bg {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animate-gradient {
            background: linear-gradient(-45deg, #f8fafc, #f1f5f9, #eff6ff, #f0fdf4);
            background-size: 400% 400%;
            animation: gradient-bg 15s ease infinite;
        }

        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            color: rgba(255, 255, 255, 0.6);
        }

        .sidebar-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.05);
            transform: translateX(8px);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, #2563eb 0%, transparent 100%);
            color: white;
            border-left: 4px solid #60a5fa;
            box-shadow: 20px 0 50px -10px rgba(37, 99, 235, 0.3);
        }

        .sidebar-link i {
            transition: transform 0.3s ease;
        }

        .sidebar-link:hover i {
            transform: scale(1.2);
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Global Scrollbar (Untuk Main Content) */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            border: 2px solid #f1f5f9;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* --- PERBAIKAN SCROLLBAR SIDEBAR --- */
        /* Menargetkan nav yang memiliki overflow-y-auto */
        nav.flex-1.overflow-y-auto::-webkit-scrollbar {
            width: 5px;
            /* Lebih tipis agar elegan */
        }

        nav.flex-1.overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
            /* Transparan agar tidak memotong warna sidebar */
        }

        nav.flex-1.overflow-y-auto::-webkit-scrollbar-thumb {
            /* Warna biru transparan yang serasi dengan tema BPS */
            background: rgba(59, 130, 246, 0.2);
            border-radius: 20px;
        }

        nav.flex-1.overflow-y-auto::-webkit-scrollbar-thumb:hover {
            /* Warna lebih terang saat di-hover */
            background: rgba(59, 130, 246, 0.5);
        }

        /* Khusus Firefox */
        nav.flex-1.overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: rgba(59, 130, 246, 0.2) transparent;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bps-modern-popup {
            border-radius: 40px !important;
            padding: 3rem !important;
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(10px);
        }

        .btn-confirm {
            background: #334155 !important;
            color: white !important;
            border-radius: 20px !important;
            padding: 14px 35px !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            letter-spacing: 0.1em !important;
            text-transform: uppercase !important;
            margin: 8px !important;
            transition: all 0.3s ease !important;
        }

        .btn-confirm:hover {
            background: #0f172a !important;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: #f1f5f9 !important;
            color: #64748b !important;
            border-radius: 20px !important;
            padding: 14px 35px !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            letter-spacing: 0.1em !important;
            text-transform: uppercase !important;
            margin: 8px !important;
            transition: all 0.3s ease !important;
        }

        .btn-cancel:hover {
            background: #e2e8f0 !important;
            color: #1e293b !important;
        }

        #main-sidebar {
            transition: transform 0.3s ease-in-out;
        }

        @media (max-width: 1023px) {
            #main-sidebar {
                transform: translateX(-100%);
            }

            #main-sidebar.active {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="animate-gradient h-screen overflow-hidden text-slate-900">
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <?php if (session()->get('login')): ?>
            <aside id="main-sidebar" class="fixed lg:relative w-72 h-full bg-slate-950 text-white flex flex-col shadow-[10px_0_30px_rgba(0,0,0,0.05)] z-50 no-print">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-500"></div>

                <div class="p-10 flex flex-col items-center">
                    <div class="relative group cursor-pointer">
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative bg-white p-4 rounded-full shadow-2xl mb-4">
                            <img src="<?= base_url('logo/Logo Badan Pusat Statistik (BPS) [RiderGalau].png') ?>" alt="Logo BPS" class="h-12 w-auto object-contain">
                        </div>
                    </div>
                    <h1 class="text-2xl font-black tracking-tighter italic text-center uppercase">BPS<span class="text-blue-500">.</span>INV</h1>
                    <p class="text-[9px] text-slate-500 font-black uppercase tracking-[0.3em] mt-1 text-center">Pemantauan Terpusat</p>
                </div>

                <nav class="flex-1 px-6 space-y-1.5 overflow-y-auto pt-2 pb-10">
                    <?php
                    $role = strtolower(trim(session()->get('role') ?? ''));
                    $dashUrl = ($role === 'super admin') ? 'superadmin/dashboard' : (($role === 'super visor') ? 'supervisor/dashboard' : 'staff/dashboard');
                    $stokUrl = (in_array($role, ['super admin', 'super visor'])) ? 'superadmin/stok' : 'staff/stok';
                    ?>

                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-4 ml-4">Menu Utama</p>

                    <a href="<?= base_url($dashUrl) ?>" class="sidebar-link <?= (url_is('*dashboard*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                        <i class="fas fa-th-large mr-4 text-lg"></i> <span class="text-sm font-bold">Dashboard</span>
                    </a>

                    <a href="<?= base_url($stokUrl) ?>" class="sidebar-link <?= (url_is('*stok*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                        <i class="fas fa-archive mr-4 text-lg"></i> <span class="text-sm font-bold">Daftar Stok</span>
                    </a>

                    <?php if ($role === 'staff' || $role === 'user'): ?>
                        <div class="pt-8 pb-3 ml-4">
                            <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em]">Service Desk</p>
                        </div>
                        <a href="<?= base_url('staff/request') ?>" class="sidebar-link <?= (url_is('*request*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                            <i class="fas fa-paper-plane mr-4 text-lg"></i> <span class="text-sm font-bold">Permintaan</span>
                        </a>
                        <a href="<?= base_url('staff/laporan') ?>" class="sidebar-link <?= (url_is('*staff/laporan*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                            <i class="fas fa-file-invoice mr-4 text-lg"></i> <span class="text-sm font-bold">History Saya</span>
                        </a>
                    <?php endif; ?>

                    <?php if (in_array($role, ['super admin', 'super visor'])): ?>
                        <div class="pt-8 pb-3 ml-4">
                            <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em]">Administrasi</p>
                        </div>
                        <a href="<?= base_url('superadmin/validasi') ?>" class="sidebar-link <?= (url_is('*validasi*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                            <div class="flex justify-between items-center w-full">
                                <div class="flex items-center">
                                    <i class="fas fa-check-double mr-4 text-lg"></i> <span class="text-sm font-bold">Validasi</span>
                                </div>
                                <?php
                                $totalPending = \Config\Database::connect()->table('requests')->where('status', 'pending')->countAllResults();
                                if ($totalPending > 0):
                                ?>
                                    <span class="bg-rose-500 text-[10px] text-white font-black px-2 py-0.5 rounded-lg shadow-lg animate-bounce"><?= $totalPending ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                        <a href="<?= base_url('superadmin/laporan') ?>" class="sidebar-link <?= (url_is('*superadmin/laporan*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                            <i class="fas fa-file-contract mr-4 text-lg"></i> <span class="text-sm font-bold">Laporan Pusat</span>
                        </a>
                        <a href="<?= base_url('superadmin/riwayat_opname') ?>" class="sidebar-link <?= (url_is('*riwayat_opname*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                            <i class="fas fa-clipboard-list mr-4 text-lg"></i> <span class="text-sm font-bold">Riwayat Audit</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($role === 'super admin'): ?>
                        <a href="<?= base_url('superadmin/categories') ?>" class="sidebar-link <?= (url_is('*categories*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                            <i class="fas fa-tags mr-4 text-lg"></i> <span class="text-sm font-bold">Kelola Kategori</span>
                        </a>
                        <a href="<?= base_url('superadmin/carousel') ?>" class="sidebar-link <?= (url_is('*carousel*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                            <i class="fas fa-photo-video mr-4 text-lg"></i> <span class="text-sm font-bold">Kelola Banner</span>
                        </a>
                        <a href="<?= base_url('superadmin/users') ?>" class="sidebar-link <?= (url_is('*users*')) ? 'active' : '' ?> w-full flex items-center py-4 px-5 rounded-2xl">
                            <i class="fas fa-user-shield mr-4 text-lg"></i> <span class="text-sm font-bold">Manajemen Pengguna</span>
                        </a>
                    <?php endif; ?>
                </nav>

                <div class="p-6 bg-black/20 border-t border-white/5">
                    <button onclick="openProfilModal()" class="w-full flex items-center p-3 rounded-2xl hover:bg-white/5 transition-all group active:scale-95">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center font-black mr-3 shrink-0 text-white shadow-lg text-xs"><?= substr(session()->get('nama') ?? 'U', 0, 1) ?></div>
                        <div class="truncate text-left flex-1">
                            <p class="font-black truncate text-[11px]"><?= esc(session()->get('nama') ?? 'User') ?></p>
                            <p class="text-[8px] text-blue-400 font-bold uppercase tracking-widest opacity-70"><?= session()->get('role') ?></p>
                        </div>
                        <i class="fas fa-external-link-alt text-[10px] text-slate-600 group-hover:text-white"></i>
                    </button>
                </div>
            </aside>
        <?php endif; ?>

        <div class="flex-1 flex flex-col overflow-hidden relative">
            <header class="glass-header border-b border-slate-200 p-4 lg:p-6 flex justify-between items-center sticky top-0 z-30 no-print">
                <div class="flex items-center gap-4 flex-1"> <?php if (session()->get('login')): ?>
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-all"><i class="fas fa-bars text-xl"></i></button>
                    <?php endif; ?>

                    <?php if (!session()->get('login')): ?>
                        <div class="bg-blue-600 p-2 rounded-xl shadow-lg"><img src="<?= base_url('logo/Logo Badan Pusat Statistik (BPS) [RiderGalau].png') ?>" class="h-6 w-auto brightness-0 invert"></div>
                    <?php endif; ?>

                    <h2 class="text-xl lg:text-2xl font-black text-slate-900 tracking-tight uppercase italic leading-relaxed py-1">
                        <?= $title ?? 'Dashboard' ?>
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <?php if (!session()->get('login')): ?>
                        <a href="<?= base_url('login') ?>" class="bg-slate-950 hover:bg-blue-700 text-white px-8 py-3 rounded-2xl shadow-2xl flex items-center font-black text-xs uppercase tracking-widest active:scale-95 transition-all"><i class="fas fa-sign-in-alt mr-2"></i> Login Langsung</a>
                    <?php else: ?>
                        <div class="relative group mr-2">
                            <button class="relative p-3 bg-slate-100 rounded-2xl border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-white transition-all duration-300">
                                <i class="fas fa-envelope text-lg"></i>
                                <?php
                                $db = \Config\Database::connect();
                                $unreadCount = $db->table('notifications')->where(['user_id' => session()->get('user_id'), 'is_read' => 0])->countAllResults();
                                if ($unreadCount > 0): ?>
                                    <span id="badge-count" class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white animate-bounce"><?= $unreadCount ?></span>
                                <?php endif; ?>
                            </button>

                            <div class="absolute right-0 mt-3 w-80 bg-white rounded-[35px] shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-500 z-50 overflow-hidden translate-y-2 group-hover:translate-y-0">
                                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                                    <h5 class="text-[11px] font-black text-slate-800 uppercase italic tracking-widest">Kotak Transaksi</h5>

                                    <button onclick="readAllNotifications()" class="text-[9px] font-black text-blue-600 uppercase hover:underline">Tandai Baca Semua</button>
                                </div>
                                <div class="max-h-96 overflow-y-auto text-xs">
                                    <?php
                                    $notifs = $db->table('notifications')->where('user_id', session()->get('user_id'))->orderBy('created_at', 'DESC')->limit(5)->get()->getResult();
                                    if ($notifs):
                                        foreach ($notifs as $n):
                                            $isMasuk = strpos(strtoupper($n->title), 'MASUK') !== false;
                                            $isKeluar = strpos(strtoupper($n->title), 'KELUAR') !== false;
                                            $isUnread = ($n->is_read == 0);
                                            $bgColor = $isMasuk ? 'bg-emerald-50' : ($isKeluar ? 'bg-blue-50' : 'bg-slate-50');
                                            $iconColor = $isMasuk ? 'text-emerald-500' : ($isKeluar ? 'text-blue-500' : 'text-slate-500');
                                            $icon = $isMasuk ? 'fa-arrow-down' : ($isKeluar ? 'fa-arrow-up' : 'fa-bell');
                                    ?>
                                            <div onclick="clickRead(<?= $n->id ?>, this)" class="notification-item p-5 border-b border-slate-50 transition-all cursor-pointer relative <?= $isUnread ? 'bg-blue-50/30 border-l-4 border-l-blue-500' : 'hover:bg-slate-50/80' ?>">
                                                <div class="flex gap-4">
                                                    <?php if ($isUnread): ?><div class="unread-dot absolute right-4 top-1/2 -translate-y-1/2 w-2 h-2 bg-blue-600 rounded-full"></div><?php endif; ?>
                                                    <div class="w-10 h-10 rounded-2xl <?= $bgColor ?> <?= $iconColor ?> flex items-center justify-center shrink-0 shadow-sm"><i class="fas <?= $icon ?> text-xs"></i></div>
                                                    <div class="flex-1 min-w-0 pr-4">
                                                        <div class="flex justify-between items-start mb-1">
                                                            <p class="title-text text-[10px] <?= $isUnread ? 'font-black text-slate-900' : 'font-bold text-slate-400' ?> uppercase italic truncate"><?= str_replace(['MASUK: ', 'KELUAR: '], '', $n->title) ?></p>
                                                            <span class="text-[8px] text-slate-400 font-bold whitespace-nowrap"><?= date('H:i', strtotime($n->created_at)) ?></span>
                                                        </div>
                                                        <p class="desc-text text-[10px] <?= $isUnread ? 'text-slate-700 font-semibold' : 'text-slate-400' ?> line-clamp-2 leading-relaxed"><?= $n->message ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;
                                    else: ?>
                                        <div class="p-12 text-center">
                                            <p class="text-[9px] font-black text-slate-300 uppercase italic tracking-widest">Belum ada aktivitas</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="hidden sm:flex items-center px-5 py-2.5 bg-slate-100 rounded-2xl border border-slate-200 shadow-inner">
                            <div class="animate-pulse w-2 h-2 bg-emerald-500 rounded-full mr-3"></div><span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Keamanan Aktif</span>
                        </div>
                    <?php endif; ?>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-10 lg:p-12">
                <div class="max-w-[1600px] mx-auto"><?= $this->renderSection('content') ?></div>
            </main>
        </div>
    </div>

    <div id="profilModal" class="hidden fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[100] flex items-center justify-center p-4">
        <div class="bg-white rounded-[50px] w-full max-w-sm overflow-hidden shadow-2xl fade-in relative border border-white/20">
            <div class="h-32 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 relative">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            </div>
            <div id="profilViewContent" class="px-10 pb-12 text-center">
                <div class="relative -mt-16 mb-6 flex justify-center">
                    <div class="w-32 h-32 rounded-[40px] bg-white p-2 shadow-2xl">
                        <div class="w-full h-full rounded-[32px] bg-gradient-to-br from-blue-500 to-indigo-700 flex items-center justify-center text-4xl font-black text-white shadow-inner"><?= substr(session()->get('nama') ?? 'U', 0, 1) ?></div>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-none"><?= esc(session()->get('nama')) ?></h3>
                <p class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] mt-2 mb-8"><?= session()->get('role') ?></p>
                <div class="grid grid-cols-1 gap-3">
                    <button onclick="switchToEdit()" class="w-full flex items-center justify-center gap-3 p-5 bg-slate-50 hover:bg-slate-100 rounded-3xl transition-all font-black text-[10px] uppercase tracking-widest text-slate-600">Ubah Profil</button>
                    <button onclick="confirmLogout()" class="w-full flex items-center justify-center gap-3 p-5 bg-rose-50 hover:bg-rose-500 group rounded-3xl transition-all"><span class="text-[10px] font-black text-rose-600 group-hover:text-white uppercase tracking-widest">Akhiri Sesi</span><i class="fas fa-power-off text-rose-600 group-hover:text-white transition-all"></i></button>
                </div>
                <button onclick="closeProfilModal()" class="mt-8 text-[9px] font-black text-slate-300 hover:text-slate-400 uppercase tracking-[0.4em]">Batal</button>
            </div>
            <div id="profilEditContent" class="hidden px-10 pb-12 pt-10">
                <form action="<?= base_url('update-profil') ?>" method="POST" class="space-y-6">
                    <?= csrf_field() ?>
                    <div class="space-y-2 text-left"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Display Nama</label><input type="text" name="nama" value="<?= esc(session()->get('nama')) ?>" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-black text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner"></div>
                    <div class="space-y-2 text-left"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Password Baru</label><input type="password" name="password" placeholder="Biarkan kosong agar tetap terkini." class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-black text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner"></div>
                    <div class="flex gap-4"><button type="button" onclick="switchToView()" class="flex-1 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Batal</button><button type="submit" class="flex-[2] bg-blue-600 text-white py-4 rounded-2xl shadow-lg font-black text-xs uppercase tracking-widest">Perbarui</button></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // --- UI INTERACTION ---
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('hidden');
        }

        const BPSNotify = Swal.mixin({
            customClass: {
                popup: 'bps-modern-popup',
                confirmButton: 'btn-confirm',
                cancelButton: 'btn-cancel'
            },
            buttonsStyling: false
        });

        function confirmLogout() {
            BPSNotify.fire({
                title: '<span class="text-slate-800 font-black italic uppercase tracking-tighter text-xl">Akhiri sesi?</span>',
                html: '<p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-2">Sesi digital Anda akan segera diakhiri.</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Akhiri',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((res) => {
                if (res.isConfirmed) window.location.href = "<?= base_url('logout') ?>";
            });
        }

        // --- NOTIFICATION SYSTEM ---
        function readAllNotifications() {
            fetch('<?= base_url('notification/readAll') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const badge = document.getElementById('badge-count');
                        if (badge) badge.style.display = 'none';

                        document.querySelectorAll('.notification-item').forEach(el => {
                            el.classList.remove('bg-blue-50/30', 'border-l-4', 'border-l-blue-500');
                            el.classList.add('hover:bg-slate-50/80');
                            const dot = el.querySelector('.unread-dot');
                            if (dot) dot.style.display = 'none';

                            const title = el.querySelector('.title-text');
                            if (title) {
                                title.classList.remove('font-black', 'text-slate-900');
                                title.classList.add('font-bold', 'text-slate-400');
                            }

                            const desc = el.querySelector('.desc-text');
                            if (desc) {
                                desc.classList.remove('text-slate-700', 'font-semibold');
                                desc.classList.add('text-slate-400');
                            }
                        });

                        BPSNotify.fire({
                            icon: 'success',
                            title: 'CLEARED',
                            text: 'Semua notifikasi telah ditandai dibaca.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
        }

        function clickRead(id, el) {
            if (el.classList.contains('bg-blue-50/30')) {
                fetch('<?= base_url('notification/markRead/') ?>' + id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            el.classList.remove('bg-blue-50/30', 'border-l-4', 'border-l-blue-500');
                            el.classList.add('hover:bg-slate-50/80');
                            const dot = el.querySelector('.unread-dot');
                            if (dot) dot.style.display = 'none';
                            const badge = document.getElementById('badge-count');
                            if (badge) {
                                let newCount = parseInt(badge.innerText) - 1;
                                newCount <= 0 ? badge.style.display = 'none' : badge.innerText = newCount;
                            }
                        }
                    });
            }
        }

        // --- PROFILE MODAL ---
        function openProfilModal() {
            switchToView();
            document.getElementById('profilModal').classList.remove('hidden');
        }

        function closeProfilModal() {
            document.getElementById('profilModal').classList.add('hidden');
        }

        function switchToEdit() {
            document.getElementById('profilViewContent').classList.add('hidden');
            document.getElementById('profilEditContent').classList.remove('hidden');
        }

        function switchToView() {
            document.getElementById('profilEditContent').classList.add('hidden');
            document.getElementById('profilViewContent').classList.remove('hidden');
        }

        window.onclick = function(e) {
            if (e.target == document.getElementById('profilModal')) closeProfilModal();
        }

        // --- IDLE TIMER & NOTIFICATION FLASH ---
        // --- IDLE TIMER SYSTEM ---
        <?php if (session()->get('login')) : ?>
            let idleTime = 0;

            function resetTimer() {
                idleTime = 0;
            }

            // Reset waktu jika ada interaksi apapun
            window.onload = resetTimer;
            window.onmousemove = resetTimer;
            window.onmousedown = resetTimer;
            window.ontouchstart = resetTimer;
            window.onclick = resetTimer;
            window.onkeypress = resetTimer;

            // Cek setiap detik
            const autoLogout = setInterval(function() {
                idleTime++;

                // Jika diam selama 900 detik (15 Menit) tanpa gerak mouse
                if (idleTime >= 900) {
                    clearInterval(autoLogout);
                    // Tendang ke halaman logout
                    window.location.href = "<?= base_url('logout') ?>";
                }
            }, 1000);
        <?php endif; ?>

        // Flash Message Success (Opsional, bawaan kamu)
        <?php if (session()->getFlashdata('success')) : ?>
            BPSNotify.fire({
                icon: 'success',
                title: 'BERHASIL',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 2000,
                showConfirmButton: false
            });
        <?php endif; ?>
    </script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>