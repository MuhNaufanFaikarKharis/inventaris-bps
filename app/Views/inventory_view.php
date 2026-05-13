<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>

<style>
    /* 1. Global & Background */
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
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

    /* 2. Carousel Profesional */
    .mainCarousel {
        height: 280px;
        width: 100%;
        transition: background 1s ease;
        position: relative;
    }

    @media (min-width: 1024px) {
        .mainCarousel {
            height: 480px;
        }
    }

    .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent !important;
    }

    .mainCarousel .swiper-slide img {
        max-width: 94%;
        max-height: 88%;
        object-fit: contain;
        z-index: 5;
        border-radius: 24px;
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.4);
    }

    /* 3. Jam Digital & Header */
    .digital-clock {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        padding: 0.8rem 1.5rem;
        border-radius: 20px;
        color: white;
        display: flex;
        align-items: center;
        box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.2);
    }

    @media (min-width: 1024px) {
        .digital-clock {
            padding: 1.2rem 2.5rem;
            border-radius: 30px;
        }
    }

    /* 4. Statistik Cards */
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 6px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    /* 5. Pagination */
    .swiper-pagination-bullet-active {
        width: 30px !important;
        border-radius: 10px !important;
        background: #fff !important;
    }

    /* 6. CUSTOMER SERVICE & LIVE CHAT UI */
    .cs-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }

    .cs-btn {
        width: 60px;
        height: 60px;
        background: #2563eb;
        color: white;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .cs-btn:hover {
        transform: scale(1.1) rotate(10deg);
        background: #1d4ed8;
    }

    .cs-menu {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 280px;
        background: white;
        border-radius: 30px;
        padding: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }

    .cs-menu.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .cs-link {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 15px;
        margin-bottom: 8px;
        transition: background 0.2s;
        cursor: pointer;
    }

    .cs-link:hover {
        background: #f8fafc;
    }

    /* Live Chat Window */
    .chat-window {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 350px;
        height: 500px;
        background: white;
        border-radius: 30px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        display: none;
        flex-direction: column;
        z-index: 1001;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        animation: fadeIn 0.3s ease-out;
    }

    .chat-header {
        background: #2563eb;
        padding: 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .chat-footer {
        padding: 15px;
        background: white;
        border-top: 1px solid #f1f5f9;
        display: flex;
        gap: 10px;
    }

    .msg {
        padding: 10px 15px;
        border-radius: 18px;
        max-width: 80%;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.4;
    }

    .msg-bot {
        background: white;
        color: #1e293b;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .msg-user {
        background: #2563eb;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }

    .chat-input {
        width: 100%;
        border: none;
        outline: none;
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
    }
</style>

<div class="fade-in flex flex-col min-h-screen space-y-6 lg:space-y-10">

    <div class="px-2 lg:px-0 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">
                Inventory <span class="text-blue-600">Monitoring</span>
            </h1>
            <p class="text-slate-500 font-medium text-xs lg:text-sm mt-2">Badan Pusat Statistik Kota Pekalongan • Unit Gudang & Logistik</p>
        </div>

        <div class="digital-clock w-full lg:w-auto justify-between lg:justify-start">
            <div class="flex flex-col items-end mr-4 lg:mr-6 pr-4 lg:pr-6 border-r border-slate-700">
                <span id="txt-date" class="text-[9px] lg:text-[11px] font-black text-blue-400 uppercase tracking-widest">Memuat...</span>
                <span class="text-[8px] lg:text-[10px] font-bold text-slate-400 uppercase">Waktu berlangsung server</span>
            </div>
            <span id="txt-time" class="text-2xl lg:text-4xl font-black tracking-tighter tabular-nums">00:00:00</span>
        </div>
    </div>

    <div class="relative group px-2 lg:px-0">
        <div id="dynamicBg" class="swiper mainCarousel rounded-[30px] lg:rounded-[60px] shadow-2xl border border-white/20">
            <div class="swiper-wrapper">
                <?php
                $db = \Config\Database::connect();
                $slides = $db->table('carousels')->where('is_active', 1)->orderBy('created_at', 'DESC')->get()->getResult();

                if (!empty($slides)):
                    foreach ($slides as $s):
                ?>
                        <div class="swiper-slide">
                            <img src="<?= base_url('uploads/carousel/' . $s->image_path) ?>"
                                class="target-img" crossOrigin="anonymous" alt="<?= esc($s->title) ?>">

                            <div class="slide-content dynamic-overlay absolute bottom-0 left-0 right-0 p-8 lg:p-16 bg-gradient-to-t from-black/80 to-transparent z-10">
                                <div class="slide-text-wrapper">
                                    <span class="bg-blue-600 text-white text-[8px] lg:text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-4 inline-block shadow-lg">Highlight</span>
                                    <h1 class="text-2xl lg:text-6xl font-black text-white uppercase italic tracking-tighter mb-2 drop-shadow-2xl">
                                        <?= esc($s->title) ?>
                                    </h1>
                                    <p class="text-white/80 text-[10px] lg:text-lg font-medium max-w-2xl italic">
                                        "Data Mencerdaskan Bangsa - Akurasi Inventaris Mendukung Kualitas Data."
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                else: ?>
                    <div class="swiper-slide bg-slate-900 flex items-center justify-center">
                        <h1 class="text-xl lg:text-3xl font-black text-white italic text-center uppercase">Tidak ada banner aktif</h1>
                    </div>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination !bottom-4 lg:!bottom-12"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8 px-2 lg:px-0">
        <div class="stat-card bg-white p-6 lg:p-8 rounded-[30px] lg:rounded-[40px] shadow-xl shadow-slate-200/50 border-l-blue-600 flex items-center">
            <div class="w-12 h-12 lg:w-16 lg:h-16 bg-blue-50 text-blue-600 rounded-2xl lg:rounded-3xl flex items-center justify-center mr-4 lg:mr-6 shadow-inner">
                <i class="fas fa-boxes text-xl lg:text-2xl"></i>
            </div>
            <div>
                <p class="text-[9px] lg:text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Katalog Jenis</p>
                <p class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tighter"><?= count($inventory ?? []) ?> Items</p>
            </div>
        </div>

        <div class="stat-card bg-white p-6 lg:p-8 rounded-[30px] lg:rounded-[40px] shadow-xl shadow-slate-200/50 border-l-emerald-600 flex items-center">
            <div class="w-12 h-12 lg:w-16 lg:h-16 bg-emerald-50 text-emerald-600 rounded-2xl lg:rounded-3xl flex items-center justify-center mr-4 lg:mr-6 shadow-inner">
                <i class="fas fa-cubes text-xl lg:text-2xl"></i>
            </div>
            <div>
                <p class="text-[9px] lg:text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Volume Stok</p>
                <?php
                $totalStok = 0;
                if (!empty($inventory)) foreach ($inventory as $item) $totalStok += (int)($item['stok'] ?? 0);
                ?>
                <p class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tighter"><?= number_format($totalStok, 0, ',', '.') ?> Pcs</p>
            </div>
        </div>

        <div class="stat-card bg-white p-6 lg:p-8 rounded-[30px] lg:rounded-[40px] shadow-xl shadow-slate-200/50 border-l-rose-600 flex items-center">
            <div class="w-12 h-12 lg:w-16 lg:h-16 bg-rose-50 text-rose-600 rounded-2xl lg:rounded-3xl flex items-center justify-center mr-4 lg:mr-6 shadow-inner">
                <i class="fas fa-exclamation-triangle text-xl lg:text-2xl"></i>
            </div>
            <div>
                <p class="text-[9px] lg:text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Perlu Restock</p>
                <?php
                $lowStok = 0;
                if (!empty($inventory)) foreach ($inventory as $item) if ($item['stok'] <= 5) $lowStok++;
                ?>
                <p class="text-2xl lg:text-3xl font-black text-rose-600 tracking-tighter"><?= $lowStok ?> Barang</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[30px] lg:rounded-[50px] shadow-2xl shadow-slate-200/60 border border-slate-100 overflow-hidden mx-2 lg:mx-0">
        <div class="p-6 lg:p-10 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center bg-slate-50/40 gap-6">
            <div class="flex items-center">
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-slate-900 text-white rounded-xl lg:rounded-2xl flex items-center justify-center mr-4 lg:mr-5 shadow-lg">
                    <i class="fas fa-list-ul text-sm"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 uppercase text-lg lg:text-xl tracking-tighter">Status Persediaan</h3>
                    <p class="text-slate-400 text-[10px] lg:text-xs font-bold uppercase tracking-widest italic">Database BPS Kota Pekalongan</p>
                </div>
            </div>
            <div class="flex items-center bg-white px-6 py-3 rounded-2xl border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500 w-full md:w-96 transition-all">
                <i class="fas fa-search text-slate-300 mr-4"></i>
                <input type="text" id="stokSearch" onkeyup="filterStok()" placeholder="Cari Nama Barang..." class="outline-none text-xs lg:text-sm font-bold text-slate-700 w-full bg-transparent">
            </div>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left min-w-[600px]" id="tableStok">
                <thead class="bg-slate-50/80 text-slate-400 text-[10px] lg:text-[11px] font-black uppercase tracking-[0.2em] border-b">
                    <tr>
                        <th class="px-6 lg:px-12 py-6">Nama Inventaris</th>
                        <th class="px-6 lg:px-12 py-6">Kategori</th>
                        <th class="px-6 lg:px-12 py-6 text-center">Indikator Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($inventory)): foreach ($inventory as $i): ?>
                            <tr class="hover:bg-blue-50/30 transition-all group">
                                <td class="px-6 lg:px-12 py-8 font-black text-slate-700 text-base lg:text-lg uppercase italic group-hover:text-blue-600 transition-colors">
                                    <?= esc($i['nama_barang']) ?>
                                </td>
                                <td class="px-6 lg:px-12 py-8">
                                    <span class="bg-slate-100 text-slate-500 text-[9px] lg:text-[10px] font-black px-4 py-2 rounded-xl uppercase tracking-widest border border-slate-200 shadow-sm">
                                        <?= esc($i['nama_kategori'] ?? 'UMUM') ?>
                                    </span>
                                </td>
                                <td class="px-6 lg:px-12 py-8 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <div class="text-2xl lg:text-3xl font-black italic <?= ($i['stok'] <= 5) ? 'text-rose-500 animate-pulse' : 'text-slate-800' ?>">
                                            <?= $i['stok'] ?>
                                        </div>
                                        <div class="h-1.5 w-12 bg-slate-100 rounded-full mt-2 overflow-hidden border border-slate-200">
                                            <div class="h-full <?= ($i['stok'] <= 5) ? 'bg-rose-500' : 'bg-emerald-500' ?>" style="width: <?= min(($i['stok'] / 20) * 100, 100) ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="3" class="px-12 py-24 text-center text-slate-300 font-black italic tracking-widest">DATABASE KOSONG</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="cs-fab no-print">
        <div id="csMenu" class="cs-menu">
            <div class="flex items-center mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white mr-3">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <h4 class="font-black text-slate-800 text-sm uppercase tracking-tighter">Support Center</h4>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Siap membantu anda</p>
                </div>
            </div>

            <a href="https://wa.me/6282328131751" target="_blank" class="cs-link group">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-800">WhatsApp Admin</p>
                    <p class="text-[9px] text-slate-400 font-bold uppercase">Respon Cepat</p>
                </div>
            </a>

            <div onclick="openLiveChat()" class="cs-link group">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-800">Live Chat Bot</p>
                    <p class="text-[9px] text-slate-400 font-bold uppercase">Bantuan Otomatis</p>
                </div>
            </div>

            <p class="text-[8px] text-slate-300 font-black uppercase text-center mt-4 tracking-[0.2em]">BPS Kota Pekalongan</p>
        </div>

        <div id="csBtn" class="cs-btn" onclick="toggleCS()">
            <i class="fas fa-comment-dots" id="csIcon"></i>
        </div>
    </div>

    <div id="liveChat" class="chat-window">
        <div class="chat-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center"><i class="fas fa-robot text-xs"></i></div>
                <span class="font-black text-xs uppercase tracking-widest">BPS Inventory Bot</span>
            </div>
            <button onclick="closeLiveChat()" class="text-white/60 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="msg msg-bot">Halo! Saya asisten digital BPS Inventory. Ada yang bisa saya bantu terkait stok atau operasional hari ini?</div>
        </div>
        <div class="chat-footer">
            <input type="text" id="chatInput" class="chat-input" placeholder="Tulis pertanyaan..." onkeypress="handleChat(event)">
            <button onclick="sendChat()" class="text-blue-600 px-2"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <div class="pt-10"></div>
    <?= $this->include('layout/footer') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Logic Jam
    function updateClock() {
        const now = new Date();
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dateStr = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
        const timeStr = now.toLocaleTimeString('id-ID', {
            hour12: false
        });
        const dateEl = document.getElementById('txt-date');
        const timeEl = document.getElementById('txt-time');
        if (dateEl) dateEl.innerText = dateStr;
        if (timeEl) timeEl.innerText = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // CUSTOMER SERVICE LOGIC
    function toggleCS() {
        const menu = document.getElementById('csMenu');
        const icon = document.getElementById('csIcon');
        menu.classList.toggle('active');

        if (menu.classList.contains('active')) {
            icon.classList.remove('fa-comment-dots');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-comment-dots');
        }
    }

    function openLiveChat() {
        document.getElementById('csMenu').classList.remove('active');
        document.getElementById('liveChat').style.display = 'flex';
        document.getElementById('csBtn').style.display = 'none';
    }

    function closeLiveChat() {
        document.getElementById('liveChat').style.display = 'none';
        document.getElementById('csBtn').style.display = 'flex';
        document.getElementById('csIcon').className = 'fas fa-comment-dots';
    }

    // CHAT BOT LOGIC
    function handleChat(e) {
        if (e.key === 'Enter') sendChat();
    }

    function sendChat(isQuickAction = false) {
        const input = document.getElementById('chatInput');
        const body = document.getElementById('chatBody');
        const val = input.value.trim();

        if (val === "") return;

        // 1. Tampilkan Pesan User
        body.innerHTML += `<div class="msg msg-user">${val}</div>`;
        const text = val.toLowerCase();
        input.value = "";
        body.scrollTop = body.scrollHeight;

        // 2. Berikan Efek Mengetik Singkat
        body.innerHTML += `<div class="msg msg-bot italic text-slate-400" id="typing">Bot sedang berpikir...</div>`;
        body.scrollTop = body.scrollHeight;

        setTimeout(() => {
            const typing = document.getElementById('typing');
            if (typing) typing.remove();

            let reply = "Mohon maaf, saya masih belajar mengenai hal itu. Silakan hubungi admin atau gunakan tombol bantuan di bawah.";

            // PESAN PEMBUKA LOGIKA (Sesuai permintaan kamu)
            let intro = isQuickAction ? "" : "<small class='text-blue-500 font-black block mb-1 uppercase tracking-tighter'>[Analisa Logika AI]</small>";

            // LOGIKA PINTAR MENGGUNAKAN DATA PHP (Ditanam langsung di JS)
            if (text.includes("stok") || text.includes("barang")) {
                reply = "Saat ini di gudang BPS tercatat ada <b><?= count($inventory) ?> jenis barang</b>. Anda bisa memantau detailnya langsung pada tabel 'Status Persediaan'.";
            } else if (text.includes("habis") || text.includes("kritis") || text.includes("sedikit")) {
                reply = "Berdasarkan data saya, ada <b><?= $lowStok ?> barang</b> yang perlu segera di-restock karena stoknya di bawah 5 pcs.";
            } else if (text.includes("halo") || text.includes("hi") || text.includes("pagi")) {
                reply = "Halo <?= session()->get('nama') ?>! Ada yang bisa saya bantu pantau dari stok inventaris hari ini?";
            } else if (text.includes("minta") || text.includes("ambil") || text.includes("prosedur")) {
                reply = "Untuk pengambilan barang, silakan ke menu 'Layanan Permintaan'. Pastikan jumlahnya tersedia ya!";
            }

            body.innerHTML += `<div class="msg msg-bot">${intro}${reply}</div>`;
            body.scrollTop = body.scrollHeight;
        }, 600); // 0.6 detik saja biar terasa cepat tapi tetap alami
    }

    // Close CS menu when clicking outside
    document.addEventListener('click', function(event) {
        const fab = document.querySelector('.cs-fab');
        const menu = document.getElementById('csMenu');
        const icon = document.getElementById('csIcon');
        const chat = document.getElementById('liveChat');
        if (fab && !fab.contains(event.target) && chat.style.display !== 'flex') {
            menu.classList.remove('active');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-comment-dots');
            }
        }
    });

    // Swiper Logic
    const colorThief = new ColorThief();
    const carouselContainer = document.getElementById('dynamicBg');
    const swiper = new Swiper(".mainCarousel", {
        loop: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        on: {
            slideChangeTransitionStart: function() {
                updateDynamicUI(this);
            }
        }
    });

    function updateDynamicUI(swiperInstance) {
        const activeSlide = swiperInstance.slides[swiperInstance.activeIndex];
        if (!activeSlide) return;
        const img = activeSlide.querySelector('.target-img');
        const overlay = activeSlide.querySelector('.dynamic-overlay');
        if (img && img.complete) {
            applyStyles(img, overlay);
        } else if (img) {
            img.addEventListener('load', () => applyStyles(img, overlay));
        }
    }

    function applyStyles(img, overlay) {
        try {
            const palette = colorThief.getPalette(img, 3);
            const rgb = palette[0];
            if (carouselContainer) carouselContainer.style.background = `linear-gradient(135deg, rgb(${rgb[0]},${rgb[1]},${rgb[2]}) 0%, #020617 100%)`;
            if (overlay) overlay.style.background = `linear-gradient(to top, rgba(${rgb[0]},${rgb[1]},${rgb[2]}, 0.95) 0%, transparent 100%)`;
        } catch (e) {
            if (carouselContainer) carouselContainer.style.background = '#0f172a';
        }
    }

    // Filter Table
    function filterStok() {
        const val = document.getElementById("stokSearch").value.toUpperCase();
        const trs = document.querySelectorAll("#tableStok tbody tr");
        trs.forEach(tr => {
            const td = tr.querySelector("td");
            if (td) {
                const txt = td.textContent || td.innerText;
                tr.style.display = txt.toUpperCase().includes(val) ? "" : "none";
            }
        });
    }

    window.addEventListener('load', () => updateDynamicUI(swiper));
</script>
<?= $this->endSection() ?>