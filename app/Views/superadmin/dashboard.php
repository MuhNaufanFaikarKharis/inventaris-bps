<?php
/**
 * @var array $inventory
 * @var array $requests
 * @var array $log
 * @var array $chart
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Mengoptimalkan performa animasi */
    .animate-pulse {
        animation-duration: 3s;
    }
</style>

<div class="fade-in space-y-6 lg:space-y-10 pb-10">

    <?php
    $criticalItems = array_filter($inventory ?? [], fn($i) => $i['stok'] <= 5);
    if (!empty($criticalItems)):
    ?>
        <div class="bg-rose-600 p-5 rounded-2xl lg:rounded-[35px] shadow-xl flex flex-col md:flex-row items-center justify-between px-6 lg:px-10 border-b-4 border-rose-800 animate-pulse gap-4">
            <div class="flex items-center gap-4 lg:gap-6 text-white text-center md:text-left">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/30 shrink-0">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div>
                    <h4 class="font-black uppercase tracking-tighter italic leading-none text-base lg:text-lg">Peringatan Inventaris: Stok Kritis!</h4>
                    <p class="text-[9px] lg:text-[10px] font-bold uppercase opacity-90 tracking-widest mt-1">Terdeteksi <?= count($criticalItems) ?> item di bawah ambang batas minimum.</p>
                </div>
            </div>
            <button onclick="openModal('tambah')" class="w-full md:w-auto px-6 py-3 bg-white text-rose-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-lg active:scale-95">
                <i class="fas fa-plus-circle mr-2"></i> Tindakan Diperlukan
            </button>
        </div>
    <?php endif; ?>

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tighter italic uppercase text-center lg:text-left">
                Konsol <span class="text-blue-600">Manajemen</span>
            </h3>
            <div class="flex items-center justify-center lg:justify-start gap-3 mt-1">
                <span class="px-2.5 py-0.5 bg-blue-600 text-white text-[10px] font-black rounded-lg uppercase tracking-widest shadow-sm">
                    <?= esc(session()->get('role')) ?>
                </span>
                <p class="text-xs lg:text-sm text-slate-500 font-medium italic">
                    Selamat <?= (date('H') < 12 ? 'Pagi' : (date('H') < 15 ? 'Siang' : (date('H') < 18 ? 'Sore' : 'Malam'))) ?>, <?= esc(explode(' ', session()->get('nama'))[0]) ?>! ✨
                </p>
            </div>
        </div>

        <div class="bg-slate-900 px-6 lg:px-8 py-4 rounded-2xl lg:rounded-[30px] shadow-2xl flex items-center justify-center gap-6 border border-white/5 w-full lg:w-auto">
            <div class="flex flex-col items-end pr-6 border-r border-slate-700">
                <p class="text-[9px] lg:text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-0.5">Status Sistem</p>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[9px] lg:text-[10px] font-bold text-emerald-500 uppercase">Koneksi Aktif</span>
                </div>
            </div>
            <div class="flex flex-col">
                <p id="realtime-clock" class="text-xl lg:text-2xl font-black text-white tracking-tighter tabular-nums leading-none">00:00:00</p>
                <p class="text-[8px] lg:text-[9px] font-bold text-slate-500 uppercase mt-1 tracking-widest"><?= date('l, d M Y') ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white p-6 lg:p-7 rounded-2xl lg:rounded-[40px] shadow-sm border border-slate-100 hover:shadow-lg transition-all">
            <div class="w-12 h-12 lg:w-14 lg:h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 lg:mb-5 shadow-inner">
                <i class="fas fa-boxes text-lg lg:text-xl"></i>
            </div>
            <p class="text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Katalog</p>
            <h4 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tighter"><?= count($inventory ?? []) ?> <span class="text-xs font-bold text-slate-300 ml-1">Barang</span></h4>
        </div>

        <div class="bg-white p-6 lg:p-7 rounded-2xl lg:rounded-[40px] shadow-sm border border-slate-100 hover:shadow-lg transition-all">
            <div class="w-12 h-12 lg:w-14 lg:h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 lg:mb-5 shadow-inner">
                <i class="fas fa-clock text-lg lg:text-xl"></i>
            </div>
            <p class="text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Permintaan Tertunda</p>
            <h4 class="text-2xl lg:text-3xl font-black text-amber-600 tracking-tighter">
                <?= count(array_filter($requests ?? [], fn($r) => $r['status'] === 'pending')) ?> <span class="text-xs font-bold text-slate-300 ml-1">Data</span>
            </h4>
        </div>

        <div class="bg-white p-6 lg:p-7 rounded-2xl lg:rounded-[40px] shadow-sm border border-slate-100 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 lg:w-14 lg:h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4 lg:mb-5 shadow-inner group-hover:bg-rose-600 group-hover:text-white transition-colors">
                <i class="fas fa-exclamation-triangle text-lg lg:text-xl"></i>
            </div>
            <p class="text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Stok Kritis</p>
            <h4 class="text-2xl lg:text-3xl font-black text-rose-600 tracking-tighter animate-pulse">
                <?= count($criticalItems) ?> <span class="text-xs font-bold text-rose-300 ml-1">Unit</span>
            </h4>
        </div>

        <div class="bg-white p-6 lg:p-7 rounded-2xl lg:rounded-[40px] shadow-sm border border-slate-100 hover:shadow-lg transition-all">
            <div class="w-12 h-12 lg:w-14 lg:h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 lg:mb-5 shadow-inner">
                <i class="fas fa-history text-lg lg:text-xl"></i>
            </div>
            <p class="text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Aktivitas</p>
            <h4 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tighter"><?= count($logs ?? []) ?> <span class="text-xs font-bold text-slate-300 ml-1">Log</span></h4>
        </div>
    </div>

    <div class="bg-white p-6 lg:p-10 rounded-3xl lg:rounded-[50px] shadow-xl border border-slate-100">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h4 class="font-black text-slate-800 uppercase tracking-tighter italic text-base">Trafik Inventaris</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Aktivitas Logistik 7 Hari Terakhir</p>
            </div>
            <div class="flex gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
                    <span class="text-[9px] font-black uppercase text-slate-500">Stok Masuk</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-rose-500 rounded-full"></span>
                    <span class="text-[9px] font-black uppercase text-slate-500">Stok Keluar</span>
                </div>
            </div>
        </div>
        <div class="h-[320px] w-full">
            <canvas id="realtimeChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2 bg-white rounded-3xl lg:rounded-[50px] border border-slate-200 overflow-hidden shadow-xl shadow-slate-200/40">
            <div class="p-6 lg:p-10 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50 gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center shadow-lg"><i class="fas fa-clipboard-check text-xs"></i></div>
                    <h4 class="font-black text-slate-800 uppercase italic tracking-tighter">Validasi Permintaan</h4>
                </div>
                <?php if (session()->get('role') === 'super admin'): ?>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button onclick="openModal('opname')" class="flex-1 sm:flex-none bg-slate-900 text-white px-4 lg:px-6 py-3 rounded-xl lg:rounded-2xl shadow-lg font-black text-[9px] lg:text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all">
                            <i class="fas fa-adjust mr-1 text-blue-400"></i> Opname
                        </button>
                        <button onclick="openModal('tambah')" class="flex-1 sm:flex-none bg-blue-600 text-white px-4 lg:px-6 py-3 rounded-xl lg:rounded-2xl shadow-lg shadow-blue-200 font-black text-[9px] lg:text-[10px] uppercase tracking-widest hover:bg-blue-700 transition-all">
                            <i class="fas fa-plus-circle mr-1"></i> Stok Masuk
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left min-w-[600px]" id="requestTable">
                    <thead class="bg-slate-50/80 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b">
                        <tr>
                            <th class="px-6 lg:px-10 py-6">Pemohon</th>
                            <th class="px-6 lg:px-10 py-6">Barang</th>
                            <th class="px-6 lg:px-10 py-6 text-center">Bukti</th>
                            <th class="px-6 lg:px-10 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (!empty($requests)): ?>
                            <?php
                            $hasPending = false;
                            foreach ($requests as $r):
                                /** @var array $r */
                                if ($r['status'] === 'pending'):
                                    $hasPending = true;
                            ?>
                                    <tr class="hover:bg-blue-50/30 transition-all group">
                                        <td class="px-6 lg:px-10 py-6 lg:py-8">
                                            <p class="font-black text-slate-700 uppercase italic text-sm group-hover:text-blue-600 transition-colors"><?= esc($r['nama_pemohon']) ?></p>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase"><?= date('d/m/Y', strtotime($r['created_at'])) ?></p>
                                        </td>
                                        <td class="px-6 lg:px-10 py-6 lg:py-8">
                                            <p class="font-bold text-slate-800 text-sm italic"><?= esc($r['nama_barang']) ?></p>
                                            <p class="text-[10px] font-black text-blue-600 uppercase">Jumlah: <?= $r['qty'] ?> Unit</p>
                                        </td>
                                        <td class="px-6 lg:px-10 py-6 lg:py-8 text-center">
                                            <?php if (!empty($r['bukti_foto'])): ?>
                                                <img src="<?= base_url('uploads/bukti_terima/' . $r['bukti_foto']) ?>"
                                                    class="w-10 h-10 object-cover rounded-xl mx-auto border border-slate-100 cursor-zoom-in"
                                                    onclick="viewImage('<?= base_url('uploads/bukti_terima/' . $r['bukti_foto']) ?>')">
                                            <?php else: ?>
                                                <i class="fas fa-camera text-slate-200"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 lg:px-10 py-6 lg:py-8 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button onclick="validaSi(<?= $r['id'] ?>, 'ditolak')" class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shrink-0">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <button onclick="validaSi(<?= $r['id'] ?>, 'disetujui')" class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl hover:bg-emerald-500 hover:text-white transition-all flex items-center justify-center shrink-0">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                            <?php endif;
                            endforeach; ?>
                            <?php if (!$hasPending): ?>
                                <tr>
                                    <td colspan="4" class="px-10 py-24 text-center text-slate-300 font-black italic uppercase tracking-widest">Semua Tugas Selesai</td>
                                </tr>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-10 py-24 text-center text-slate-300 font-black italic uppercase tracking-widest">Tidak Ada Data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-6 lg:p-10 rounded-3xl lg:rounded-[50px] shadow-xl shadow-slate-200/40 border border-slate-100 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h4 class="font-black text-slate-800 uppercase tracking-tighter italic text-base">Linimasa</h4>
                <button onclick="readAllNotifications()" class="text-[9px] font-black text-blue-600 uppercase hover:underline tracking-widest">
                    Tandai Semua Dibaca
                </button>
            </div>
            <div class="space-y-4 flex-1">
                <?php if (!empty($logs)): ?>
                    <?php foreach (array_slice($logs, 0, 6) as $log): ?>
                        <?php /** @var array $log */ ?>
                        <div class="flex items-start gap-4 p-3 lg:p-4 rounded-2xl lg:rounded-3xl hover:bg-slate-50 transition-all border border-transparent group">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-inner <?= $log['tipe'] === 'Masuk' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' ?>">
                                <i class="fas <?= $log['tipe'] === 'Masuk' ? 'fa-arrow-down' : 'fa-arrow-up' ?> text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-black text-slate-700 uppercase italic truncate group-hover:text-blue-600 transition-colors"><?= esc($log['nama_barang']) ?></p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase mt-1 truncate"><?= esc($log['nama_pelaku']) ?> • <?= $log['qty'] ?> Pack</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a href="<?= base_url('superadmin/laporan') ?>" class="mt-6 group flex items-center justify-center gap-3 py-4 bg-slate-900 rounded-2xl text-[10px] font-black text-white hover:bg-blue-600 transition-all uppercase tracking-[0.2em] shadow-lg">
                Lihat Laporan <i class="fas fa-chevron-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>

<div id="modalTambahStok" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl lg:rounded-[50px] w-full max-w-md p-6 lg:p-10 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-blue-600"></div>
        <div class="flex justify-between items-center mb-6 lg:mb-8">
            <h3 class="text-xl lg:text-2xl font-black text-slate-900 uppercase tracking-tighter italic">Input Stok Masuk</h3>
            <button onclick="closeModal()" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center hover:text-rose-500 transition-all">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <form action="<?= base_url('superadmin/save_tambah') ?>" method="POST" class="space-y-4 lg:space-y-6">
            <?= csrf_field() ?>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Pilih Barang</label>
                <select name="nama" required class="w-full bg-slate-50 border-none rounded-xl lg:rounded-[20px] px-6 py-4 text-sm font-bold text-slate-700 shadow-inner outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>-- Cari Barang --</option>
                    <?php foreach ($inventory as $inv): ?>
                        <?php /** @var array $inv */ ?>
                        <option value="<?= esc($inv['nama_barang']) ?>"><?= esc($inv['nama_barang']) ?> (Stok: <?= $inv['stok'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Jumlah Masuk</label>
                <div class="relative flex items-center">
                    <input type="number" name="qty" required min="1" class="w-full bg-slate-50 border-none rounded-xl lg:rounded-[20px] px-6 py-4 text-sm font-black text-slate-900 shadow-inner outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="absolute right-6 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-white px-2 py-1 rounded-md">Pack</span>
                </div>
            </div>
            <button type="submit" class="w-full py-4 lg:py-5 bg-blue-600 text-white font-black text-xs rounded-xl lg:rounded-[25px] shadow-xl hover:bg-blue-700 transition-all uppercase tracking-[0.2em]">Perbarui Database</button>
        </form>
    </div>
</div>

<div id="modalOpname" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl lg:rounded-[50px] w-full max-w-md p-6 lg:p-10 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-slate-900"></div>
        <div class="flex justify-between items-center mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-black text-slate-900 uppercase tracking-tighter italic leading-none">Stock Opname</h3>
                <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 italic">Audit Stok Fisik</p>
            </div>
            <button onclick="closeModalOpname()" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center hover:text-rose-500 transition-all">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <form action="<?= base_url('superadmin/save_opname') ?>" method="POST" class="space-y-4 lg:space-y-6">
            <?= csrf_field() ?>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Pilih Barang Audit</label>
                <select name="inventory_id" required class="w-full bg-slate-50 border-none rounded-xl lg:rounded-[20px] px-6 py-4 text-sm font-bold text-slate-700 shadow-inner outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>-- Pilih Item --</option>
                    <?php foreach ($inventory as $inv): ?>
                        <?php /** @var array $inv */ ?>
                        <option value="<?= $inv['id'] ?>"><?= esc($inv['nama_barang']) ?> (Sistem: <?= $inv['stok'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] ml-2">Jumlah Fisik di Gudang</label>
                <input type="number" name="stok_fisik" required min="0" placeholder="Masukkan jumlah asli..." class="w-full bg-blue-50 border-none rounded-xl lg:rounded-[20px] px-6 py-4 text-sm font-black text-slate-900 shadow-inner outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Keterangan</label>
                <textarea name="keterangan" required class="w-full bg-slate-50 border-none rounded-xl lg:rounded-[20px] px-6 py-4 text-sm font-medium text-slate-700 shadow-inner outline-none focus:ring-2 focus:ring-blue-500" placeholder="Alasan selisih..."></textarea>
            </div>
            <button type="submit" class="w-full py-4 lg:py-5 bg-slate-900 text-white font-black text-xs rounded-xl lg:rounded-[25px] shadow-xl hover:bg-blue-600 transition-all uppercase tracking-[0.2em]">SINKRONKAN DATA</button>
        </form>
    </div>
</div>

<div id="imageModal" class="hidden fixed inset-0 bg-slate-900/90 backdrop-blur-md z-[200] flex items-center justify-center p-4 transition-all" onclick="closeImageModal()">
    <div class="max-w-2xl w-full fade-in">
        <img id="imgZoom" src="" class="w-full h-auto rounded-2xl lg:rounded-[40px] shadow-2xl border-4 border-white/20">
        <p class="text-white text-center mt-6 font-black text-[10px] uppercase tracking-[0.3em] italic opacity-50">Klik di mana saja untuk menutup</p>
    </div>
</div>

<script>
    // DATA DARI CONTROLLER UNTUK GRAFIK
    const chartLabels = <?= json_encode($chart['labels'] ?? []) ?>;
    const dataMasuk = <?= json_encode($chart['masuk'] ?? []) ?>;
    const dataKeluar = <?= json_encode($chart['keluar'] ?? []) ?>;

    const ctx = document.getElementById('realtimeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                    label: 'Stok Masuk',
                    data: dataMasuk,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.05)',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4
                },
                {
                    label: 'Pengeluaran',
                    data: dataKeluar,
                    borderColor: '#f43f5e',
                    backgroundColor: 'rgba(244, 63, 94, 0.05)',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f43f5e',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 10,
                    titleFont: {
                        size: 12,
                        weight: 'bold'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        color: '#94a3b8'
                    }
                }
            }
        }
    });

    // FUNGSI JAM REALTIME
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

    // FUNGSI MODAL
    function openModal(type) {
        if (type === 'tambah') document.getElementById('modalTambahStok').classList.remove('hidden');
        if (type === 'opname') document.getElementById('modalOpname').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalTambahStok').classList.add('hidden');
    }

    function closeModalOpname() {
        document.getElementById('modalOpname').classList.add('hidden');
    }

    // FUNGSI VALIDASI DENGAN SWEETALERT
    function validaSi(id, status) {
        Swal.fire({
            title: `Konfirmasi ${status.toUpperCase()}?`,
            text: status === 'disetujui' ? 'Menyetujui permintaan akan memotong stok barang.' : 'Permintaan ini akan ditolak.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: status === 'disetujui' ? '#10b981' : '#f43f5e',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Konfirmasi',
            customClass: {
                popup: 'rounded-[30px] lg:rounded-[40px]'
            }
        }).then((result) => {
            if (result.isConfirmed) window.location.href = "<?= base_url('superadmin/update_status') ?>/" + id + "/" + status;
        });
    }

    // FUNGSI ZOOM GAMBAR
    function viewImage(src) {
        document.getElementById('imgZoom').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Tutup modal saat klik area luar
    window.onclick = function(e) {
        if (e.target == document.getElementById('modalTambahStok')) closeModal();
        if (e.target == document.getElementById('modalOpname')) closeModalOpname();
    }
</script>
<?= $this->endSection() ?>