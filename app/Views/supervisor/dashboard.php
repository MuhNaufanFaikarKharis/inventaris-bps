<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
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

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>

<div class="fade-in space-y-8 lg:space-y-10 pb-10">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter italic uppercase leading-none">
                Audit <span class="text-blue-600">Dashboard</span>
            </h3>
            <div class="flex items-center gap-3 mt-2">
                <span class="px-2.5 py-0.5 bg-emerald-500 text-white text-[9px] font-black rounded-lg uppercase tracking-widest shadow-sm shadow-emerald-200">
                    Supervisor Mode
                </span>
                <p class="text-sm text-slate-500 font-medium italic">
                    Selamat <?= (date('H') < 12 ? 'Pagi' : (date('H') < 15 ? 'Siang' : (date('H') < 18 ? 'Sore' : 'Malam'))) ?>, <?= esc(explode(' ', session()->get('nama'))[0]) ?>.
                </p>
            </div>
        </div>

        <div class="bg-white px-8 py-4 rounded-[30px] shadow-xl shadow-slate-200/50 flex items-center gap-6 border border-slate-100">
            <div class="flex flex-col items-end pr-6 border-r border-slate-200">
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-0.5">Audit Mode Aktif</p>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase italic">Keamanan</span>
                </div>
            </div>
            <div class="flex flex-col">
                <p id="realtime-clock" class="text-2xl font-black text-slate-800 tracking-tighter tabular-nums leading-none">00:00:00</p>
                <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 tracking-widest"><?= date('l, d M Y') ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-7 rounded-[40px] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-5 shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fas fa-database text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Aset</p>
            <h4 class="text-3xl font-black text-slate-900 tracking-tighter"><?= count($inventory ?? []) ?> <span class="text-sm font-bold text-slate-300 ml-1">Jenis</span></h4>
        </div>

        <div class="bg-white p-7 rounded-[40px] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-5 shadow-inner group-hover:bg-rose-600 group-hover:text-white transition-all">
                <i class="fas fa-exclamation-circle text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Stok Kritis</p>
            <h4 class="text-3xl font-black text-rose-600 tracking-tighter">
                <?= count(array_filter($inventory ?? [], fn($i) => $i['stok'] < 5)) ?> <span class="text-sm font-bold text-rose-300 ml-1">Item</span>
            </h4>
        </div>

        <div class="bg-white p-7 rounded-[40px] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-5 shadow-inner group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fas fa-file-export text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Log Keluar</p>
            <h4 class="text-3xl font-black text-slate-900 tracking-tighter">
                <?= count(array_filter($logs ?? [], fn($l) => $l['tipe'] === 'Keluar')) ?> <span class="text-sm font-bold text-slate-300 ml-1">Log</span>
            </h4>
        </div>

        <div class="bg-white p-7 rounded-[40px] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-5 shadow-inner group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i class="fas fa-user-check text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sistem Otoritas</p>
            <h4 class="text-3xl font-black text-slate-900 tracking-tighter italic uppercase">Aktif</h4>
        </div>
    </div>

    <div class="bg-white p-6 lg:p-10 rounded-3xl lg:rounded-[50px] shadow-xl border border-slate-100">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h4 class="font-black text-slate-800 uppercase tracking-tighter italic text-base">Audit Traffic</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Aktivitas Logistik 7 Hari Terakhir</p>
            </div>
            <div class="flex gap-4">
                <div class="flex items-center gap-2"><span class="w-3 h-3 bg-blue-600 rounded-full"></span><span class="text-[9px] font-black uppercase text-slate-500">Masuk</span></div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 bg-rose-500 rounded-full"></span><span class="text-[9px] font-black uppercase text-slate-500">Keluar</span></div>
            </div>
        </div>
        <div class="h-[300px] w-full">
            <canvas id="supervisorChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-[50px] border border-slate-200 overflow-hidden shadow-xl shadow-slate-200/40">
            <div class="p-10 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center bg-slate-50/50 gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center shadow-lg"><i class="fas fa-shield-alt text-xs"></i></div>
                    <h4 class="font-black text-slate-800 uppercase italic tracking-tighter text-sm">Inventory Audit List</h4>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b">
                        <tr>
                            <th class="px-10 py-6">Informasi Aset</th>
                            <th class="px-10 py-6 text-center">Stok</th>
                            <th class="px-10 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (!empty($inventory)): ?>
                            <?php foreach ($inventory as $i): ?>
                                <tr class="hover:bg-slate-50 transition-all group">
                                    <td class="px-10 py-8">
                                        <p class="font-black text-slate-700 uppercase italic leading-none mb-1"><?= esc($i['nama_barang']) ?></p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase"><?= esc($i['nama_kategori'] ?? 'Umum') ?></p>
                                    </td>
                                    <td class="px-10 py-8 text-center">
                                        <span class="font-black text-slate-800 text-lg"><?= $i['stok'] ?></span>
                                    </td>
                                    <td class="px-10 py-8 text-right">
                                        <button onclick="confirmDelete(<?= $i['id'] ?>, '<?= esc($i['nama_barang']) ?>')"
                                            class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center ml-auto">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-10 rounded-[50px] shadow-xl shadow-slate-200/40 border border-slate-100 flex flex-col">
            <h4 class="font-black text-slate-800 uppercase tracking-tighter italic text-base mb-6">Recent Logs</h4>
            <div class="space-y-4 flex-1 overflow-y-auto pr-2 custom-scrollbar max-h-[400px]">
                <?php if (!empty($logs)): ?>
                    <?php foreach (array_slice($logs, 0, 8) as $log): ?>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 <?= $log['tipe'] === 'Masuk' ? 'bg-emerald-400' : 'bg-rose-400' ?>"></div>
                            <p class="text-[10px] font-black text-slate-700 uppercase italic"><?= esc($log['nama_barang']) ?></p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase"><?= $log['tipe'] ?> • <?= $log['qty'] ?> Unit</p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a href="<?= base_url('superadmin/laporan') ?>" class="mt-6 py-4 bg-blue-600 rounded-2xl text-[10px] font-black text-white text-center uppercase tracking-widest shadow-lg shadow-blue-200">View Full Reports</a>
        </div>
    </div>
</div>

<script>
    // DATA GRAFIK DARI CONTROLLER
    const chartLabels = <?= json_encode($chart['labels'] ?? []) ?>;
    const dataMasuk = <?= json_encode($chart['masuk'] ?? []) ?>;
    const dataKeluar = <?= json_encode($chart['keluar'] ?? []) ?>;

    const ctx = document.getElementById('supervisorChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                    label: 'Masuk',
                    data: dataMasuk,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.05)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4
                },
                {
                    label: 'Keluar',
                    data: dataKeluar,
                    borderColor: '#f43f5e',
                    backgroundColor: 'rgba(244, 63, 94, 0.05)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
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
                            size: 10
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });

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

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'HAPUS DATA?',
            text: `Data '${nama}' akan dihapus secara permanen dari audit.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            confirmButtonText: 'HAPUS',
            customClass: {
                popup: 'rounded-[30px]'
            }
        }).then((result) => {
            if (result.isConfirmed) window.location.href = "<?= base_url('supervisor/delete') ?>/" + id;
        });
    }
</script>
<?= $this->endSection() ?>