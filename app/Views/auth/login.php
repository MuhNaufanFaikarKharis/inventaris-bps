<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login BPS Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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
            background-size: 300% 300%;
            animation: gradient-bg 12s ease infinite;
        }
    </style>
</head>

<body class="animate-gradient bg-gradient-to-br from-sky-400 via-emerald-300 to-orange-300 flex items-center justify-center min-h-screen overflow-hidden">

    <div class="bg-white/80 backdrop-blur-md p-10 rounded-[2.5rem] shadow-2xl w-full max-w-md border border-white/20 transition-all duration-500 mx-4">

        <div class="flex flex-col items-center mb-8">
            <div class="bg-white p-5 rounded-full shadow-xl mb-5 ring-4 ring-emerald-100/50">
                <img src="<?= base_url('logo/Logo Badan Pusat Statistik (BPS) [RiderGalau].png') ?>"
                    alt="Logo BPS"
                    class="h-24 w-auto object-contain">
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight text-center">BPS Inventory</h2>
            <p class="text-slate-600 text-sm font-bold uppercase tracking-[0.2em] mt-1.5 text-center">Kota Pekalongan</p>
        </div>

        <!-- NOTIFIKASI SUCCESS -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-2xl mb-6 text-sm font-bold flex items-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- NOTIFIKASI ERROR (Termasuk notif Akun Pending) -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-2xl mb-6 text-sm font-bold flex items-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        

        <form action="/login-process" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div class="relative">
                <input type="email" name="email" placeholder="Email Address" required
                    class="w-full p-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-400/20 focus:border-emerald-400 outline-none transition-all placeholder:text-slate-400">
            </div>

            <div class="relative">
                <input type="password" name="password" placeholder="Password" required
                    class="w-full p-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-400/20 focus:border-emerald-400 outline-none transition-all placeholder:text-slate-400">
            </div>

            <button type="submit"
                class="w-full bg-slate-800 text-white py-4 rounded-2xl font-bold hover:bg-slate-900 shadow-lg hover:shadow-emerald-500/20 transition-all transform active:scale-[0.98] mt-2">
                Login
            </button>
        </form>

        <div class="mt-6 text-center space-y-2">
            <p class="text-sm text-slate-600 font-medium">
                Lupa password?
                <a href="/forgot-password" class="text-rose-500 font-black hover:text-rose-700 transition-colors ml-1 underline decoration-2 underline-offset-4">
                    Reset Disini
                </a>
            </p>
            <p class="text-sm text-slate-600 font-medium">
                Belum punya akun?
                <a href="/register" class="text-blue-600 font-bold hover:text-blue-800 transition-colors ml-1 underline decoration-2 underline-offset-4">
                    Daftar Sekarang
                </a>
            </p>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">
                Aplikasi Inventaris ATK & BMN
            </span>
        </div>
    </div>

</body>

</html>