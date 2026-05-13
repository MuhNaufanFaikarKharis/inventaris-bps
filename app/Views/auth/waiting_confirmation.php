<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Konfirmasi - BPS Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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

    <div class="bg-white/80 backdrop-blur-md p-8 md:p-10 rounded-[2.5rem] shadow-2xl w-full max-w-md border border-white/20 text-center mx-4">
        
        <div class="flex justify-center mb-6">
            <div class="bg-emerald-100 p-4 rounded-full text-emerald-600 ring-4 ring-emerald-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-4">Registrasi Berhasil!</h2>
        
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 text-left">
            <p class="text-sm text-amber-800 leading-relaxed font-medium">
                Akun kamu telah berhasil dibuat, namun <strong class="text-amber-900">belum dapat digunakan untuk login</strong>.
            </p>
            <p class="text-sm text-amber-800 leading-relaxed mt-2">
                Silakan hubungi <strong>Superadmin</strong> untuk melakukan validasi dan pemberian akses pada akun kamu.
            </p>
        </div>

        <a href="/login" class="inline-block w-full bg-slate-800 text-white py-4 rounded-2xl font-bold hover:bg-slate-900 shadow-lg transition-all transform active:scale-[0.98]">
            Kembali ke Halaman Login
        </a>
    </div>

</body>
</html>