<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="fade-in space-y-10 pb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter italic uppercase">
                Internal <span class="text-blue-600">Console</span>
            </h3>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-black rounded uppercase tracking-widest">Staff Area</span>
                <p class="text-sm text-slate-400 font-medium">Monitoring & Logistik BPS Kota Pekalongan</p>
            </div>
        </div>

        <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-[30px] border border-slate-100 shadow-xl shadow-slate-200/40">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-blue-200">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Selamat <?= (date('H') < 12 ? 'Pagi' : (date('H') < 15 ? 'Siang' : (date('H') < 18 ? 'Sore' : 'Malam'))) ?>,</p>
                <p class="text-sm font-black text-slate-800 italic"><?= esc(session()->get('nama')) ?> ✨</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-7 rounded-[40px] shadow-sm border border-slate-100 group hover:border-blue-500/30 transition-all duration-300">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-inner">
                <i class="fas fa-boxes text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Katalog</p>
            <h4 class="text-3xl font-black text-slate-900 tracking-tighter"><?= count($inventory ?? []) ?> <span class="text-sm font-bold text-slate-300 ml-1">Items</span></h4>
        </div>

        <div class="bg-white p-7 rounded-[40px] shadow-sm border border-slate-100 group hover:border-amber-500/30 transition-all duration-300">
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-inner">
                <i class="fas fa-history text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Permintaan Saya</p>
            <h4 class="text-3xl font-black text-slate-900 tracking-tighter">
                <?= count($requests ?? []) ?> <span class="text-sm font-bold text-slate-300 ml-1">Data</span>
            </h4>
        </div>

        <div class="bg-white p-7 rounded-[40px] shadow-sm border border-slate-100 group hover:border-emerald-500/30 transition-all duration-300">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-inner">
                <i class="fas fa-check-double text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Approved</p>
            <h4 class="text-3xl font-black text-slate-900 tracking-tighter">
                <?= count(array_filter($requests ?? [], fn($r) => $r['status'] === 'disetujui')) ?> <span class="text-sm font-bold text-slate-300 ml-1">Items</span>
            </h4>
        </div>

        <div class="bg-slate-900 p-7 rounded-[40px] shadow-2xl shadow-slate-300 flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-1">System Time</p>
                <h4 id="realtime-clock" class="text-2xl font-black text-white tracking-tighter tabular-nums">00:00:00</h4>
            </div>
            <p class="text-[10px] font-bold text-slate-500 z-10 uppercase italic mt-4"><?= date('l, d F Y') ?></p>
            <i class="fas fa-clock absolute right-[-10px] bottom-[-10px] text-6xl text-white/5 -rotate-12"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-[50px] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            <div class="p-10 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                <div>
                    <h4 class="font-black text-slate-800 uppercase tracking-tighter italic text-base">Status Permintaan Terakhir</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Pantau proses pengajuan barang Anda</p>
                </div>
                <a href="<?= base_url('staff/request') ?>" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
                    <i class="fas fa-external-link-alt text-xs"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 text-[9px] uppercase tracking-widest font-black text-slate-400 border-b">
                        <tr>
                            <th class="px-10 py-5">Barang</th>
                            <th class="px-10 py-5 text-center">Jumlah</th>
                            <th class="px-10 py-5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        <?php if (!empty($requests)): ?>
                            <?php foreach (array_slice($requests, 0, 5) as $req): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-10 py-6 font-bold text-slate-700 uppercase italic text-xs"><?= esc($req['nama_barang']) ?></td>
                                    <td class="px-10 py-6 text-center font-black text-slate-400"><?= $req['qty'] ?> <span class="text-[10px]">Unit</span></td>
                                    <td class="px-10 py-6 text-center">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter 
                                            <?= $req['status'] === 'pending' ? 'bg-amber-50 text-amber-500' : ($req['status'] === 'disetujui' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500') ?>">
                                            <?= $req['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="px-10 py-20 text-center text-slate-300 italic font-bold">Belum ada riwayat permintaan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-8 flex flex-col">
            <div class="bg-blue-600 p-8 rounded-[50px] shadow-2xl shadow-blue-200 relative overflow-hidden group">
                <i class="fas fa-paper-plane absolute right-[-20px] top-[-20px] text-8xl text-white/10 -rotate-12 group-hover:rotate-0 transition-transform duration-500"></i>
                <div class="relative z-10">
                    <h4 class="text-white font-black uppercase tracking-tighter italic text-lg mb-2">Butuh ATK?</h4>
                    <p class="text-blue-100 text-xs font-medium leading-relaxed opacity-80 mb-6">Ajukan permintaan barang sekarang untuk mendukung kelancaran pekerjaan Anda.</p>
                    <a href="<?= base_url('staff/request') ?>" class="inline-flex items-center gap-3 px-6 py-3 bg-white text-blue-600 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg hover:bg-slate-900 hover:text-white transition-all">
                        Request Now <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[50px] shadow-xl shadow-slate-200/40 border border-slate-100 flex-1">
                <div class="mb-6">
                    <h4 class="font-black text-slate-800 uppercase tracking-tighter italic text-base leading-none mb-1">Info Stok Kritis</h4>
                    <p class="text-[10px] text-rose-500 font-bold uppercase tracking-widest">Segera ambil sebelum habis</p>
                </div>

                <div class="space-y-4">
                    <?php if (!empty($inventory)): ?>
                        <?php
                        // Urutkan stok tersedikit
                        usort($inventory, fn($a, $b) => $a['stok'] <=> $b['stok']);
                        foreach (array_slice($inventory, 0, 4) as $item):
                        ?>
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50/50 border border-slate-100">
                                <span class="text-[11px] font-bold text-slate-600 uppercase italic truncate max-w-[120px]"><?= esc($item['nama_barang']) ?></span>
                                <span class="text-[10px] font-black <?= $item['stok'] <= 5 ? 'text-rose-500' : 'text-blue-500' ?>">
                                    <?= $item['stok'] ?> <span class="text-[8px] opacity-50">Sisa</span>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <a href="<?= base_url('stok') ?>" class="mt-8 group flex items-center justify-center gap-3 py-4 border-2 border-slate-100 rounded-[20px] text-[9px] font-black text-slate-400 hover:border-blue-600 hover:text-blue-600 transition-all uppercase tracking-[0.2em]">
                    Cek Seluruh Stok <i class="fas fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        const clockEl = document.getElementById('realtime-clock');
        if (clockEl) clockEl.innerText = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

<?= $this->endSection() ?>