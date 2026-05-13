<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register BPS Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @keyframes gradient-bg {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animate-gradient {
            background-size: 300% 300%;
            animation: gradient-bg 12s ease infinite;
        }
    </style>
</head>

<body class="animate-gradient bg-gradient-to-br from-sky-400 via-emerald-300 to-orange-300 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white/80 backdrop-blur-md p-8 md:p-10 rounded-[2.5rem] shadow-2xl w-full max-w-md border border-white/20 transition-all duration-500 mx-4">

        <div class="flex flex-col items-center mb-6">
            <div class="bg-white p-5 rounded-full shadow-xl mb-5 ring-4 ring-emerald-100/50">
                <img src="<?= base_url('logo/Logo Badan Pusat Statistik (BPS) [RiderGalau].png') ?>"
                    alt="Logo BPS"
                    class="h-16 w-auto object-contain">
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight text-center">Buat Akun</h2>
            <p class="text-slate-600 text-sm font-bold uppercase tracking-[0.2em] mt-1.5 text-center">Kota Pekalongan</p>
        </div>

        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-6">
            <h3 class="text-[12px] font-black text-emerald-700 uppercase tracking-wider mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Panduan Pengisian
            </h3>
            <ul class="text-[11px] text-emerald-800 space-y-1 font-medium italic">
                <li>• Gunakan <strong>Email Pribadi</strong> yang masih aktif.</li>
                <li>• <strong>Hobi</strong> sangat penting untuk reset password.</li>
            </ul>
        </div>

        <form action="/register-process" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            
            <div class="relative">
                <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required
                    class="w-full p-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-400/20 focus:border-emerald-400 outline-none transition-all placeholder:text-slate-400 text-sm">
            </div>

            <div class="relative">
                <input type="email" name="email" placeholder="Alamat Email (Pribadi)" required
                    class="w-full p-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-400/20 focus:border-emerald-400 outline-none transition-all placeholder:text-slate-400 text-sm">
            </div>

            <div class="relative">
                <input type="text" name="username" placeholder="Username" required
                    class="w-full p-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-400/20 focus:border-emerald-400 outline-none transition-all placeholder:text-slate-400 text-sm">
            </div>

            <div class="relative">
                <input type="password" name="password" placeholder="Password" required
                    class="w-full p-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-400/20 focus:border-emerald-400 outline-none transition-all placeholder:text-slate-400 text-sm">
            </div>

            <div class="relative">
                <input type="text" name="security_answer" placeholder="Apa hobi kamu?" required
                    class="w-full p-4 bg-white/50 border border-rose-200 rounded-2xl focus:ring-4 focus:ring-rose-400/20 focus:border-rose-400 outline-none transition-all placeholder:text-rose-400 text-sm">
            </div>

            <button type="submit"
                class="w-full bg-slate-800 text-white py-4 rounded-2xl font-bold hover:bg-slate-900 shadow-lg hover:shadow-emerald-500/20 transition-all transform active:scale-[0.98] mt-2">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-slate-600 font-medium">
                Sudah punya akun?
                <a href="/login" class="text-blue-600 font-bold hover:text-blue-800 transition-colors ml-1 underline decoration-2 underline-offset-4">
                    Login Disini
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