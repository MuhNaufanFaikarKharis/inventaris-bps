<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - BPS Inventory</title>
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

<body class="animate-gradient bg-gradient-to-br from-sky-400 via-emerald-300 to-orange-300 flex items-center justify-center h-screen overflow-hidden">

    <div class="bg-white/80 backdrop-blur-md p-10 rounded-[2.5rem] shadow-2xl w-full max-w-md border border-white/20 transition-all duration-500">

        <div class="flex flex-col items-center mb-8">
            <div class="bg-white p-4 rounded-full shadow-lg mb-4">
                <img src="<?= base_url('logo/Logo Badan Pusat Statistik (BPS) [RiderGalau].png') ?>"
                    alt="Logo BPS" class="h-16 w-auto object-contain">
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none">Self Reset</h2>
            <p class="text-slate-600 text-[10px] font-bold uppercase tracking-[0.2em] mt-2">BPS Kota Pekalongan</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100/80 text-red-600 p-3 rounded-2xl mb-6 text-sm border border-red-200 text-center font-bold">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="/forgot-process" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div class="relative">
                <input type="email" name="email" placeholder="Alamat Email Terdaftar"
                    class="w-full p-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-400/20 outline-none transition-all placeholder:text-slate-400 font-medium" required>
            </div>

            <div class="p-5 bg-blue-50/50 rounded-3xl border border-blue-100/50">
                <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1 italic">Security Question:</p>
                <p class="text-xs font-bold text-slate-700">Siapa nama ibu kandung Anda?</p>
            </div>

            <div class="relative">
                <input type="text" name="answer" placeholder="Jawaban Keamanan"
                    class="w-full p-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-400/20 outline-none transition-all placeholder:text-slate-400 font-medium" required>
            </div>

            <div class="py-2">
                <hr class="border-slate-100">
            </div>

            <div class="relative">
                <input type="password" name="new_password" placeholder="Password Baru"
                    class="w-full p-4 bg-slate-100/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-400/20 outline-none transition-all placeholder:text-slate-400 font-bold" required>
            </div>

            <button type="submit"
                class="w-full bg-slate-800 text-white py-4 rounded-2xl font-bold hover:bg-slate-900 shadow-lg hover:shadow-blue-500/20 transition-all transform active:scale-[0.98] mt-2">
                Update Password
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="/login" class="text-sm text-slate-400 font-bold uppercase tracking-widest hover:text-slate-800 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Login
            </a>
        </div>

    </div>

</body>

</html>