<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-6">
    <div class="flex justify-between items-center no-print">
        <div>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight uppercase italic">Laporan Penggunaan Saya</h3>
            <p class="text-sm text-slate-500 font-medium">Rekapitulasi barang yang telah Anda ambil dan gunakan.</p>
        </div>
        <button onclick="window.print()" class="bg-slate-800 text-white px-6 py-3 rounded-2xl shadow-lg font-bold text-sm hover:bg-black transition-all">
            <i class="fas fa-print mr-2"></i> Cetak Riwayat
        </button>
    </div>

    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-200 no-print">
        <form action="<?= base_url('staff/laporan') ?>" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Dari Tanggal</label>
                <input type="date" name="tgl_awal" value="<?= $tgl_awal ?? '' ?>"
                    class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 shadow-inner focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Sampai Tanggal</label>
                <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?? '' ?>"
                    class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 shadow-inner focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg active:scale-95">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <?php if (!empty($tgl_awal)): ?>
                    <a href="<?= base_url('staff/laporan') ?>" class="bg-slate-100 text-slate-500 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="bg-blue-600 rounded-[32px] p-8 text-white shadow-xl shadow-blue-100 no-print">
        <div class="flex items-center gap-6">
            <div class="bg-white/20 p-4 rounded-2xl">
                <i class="fas fa-file-invoice text-3xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest opacity-80">Total Pengambilan Barang</p>
                <h4 class="text-3xl font-black"><?= count($logs) ?> <span class="text-sm font-normal opacity-70">Transaksi Terdaftar</span></h4>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[40px] border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-8 border-b bg-slate-50/50 flex justify-between items-center">
            <h4 class="font-bold text-slate-800 uppercase italic tracking-tighter">Detail Mutasi Barang Anda</h4>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <?= (!empty($tgl_awal)) ? "Periode: " . date('d/m/Y', strtotime($tgl_awal)) . " - " . date('d/m/Y', strtotime($tgl_akhir)) : "BPS Pekalongan" ?>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest font-bold border-b">
                    <tr>
                        <th class="px-8 py-5">Waktu Pengambilan</th>
                        <th class="px-8 py-5">Nama Barang</th>
                        <th class="px-8 py-5 text-center">Jumlah</th>
                        <th class="px-8 py-5 text-right">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="px-8 py-6">
                                    <span class="block font-bold text-slate-700"><?= date('d/m/Y', strtotime($log['created_at'])) ?></span>
                                    <span class="text-[10px] text-slate-400"><?= date('H:i', strtotime($log['created_at'])) ?> WIB</span>
                                </td>
                                <td class="px-8 py-6 uppercase font-black text-blue-600">
                                    <?= esc($log['nama_barang']) ?>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="bg-slate-100 px-3 py-1 rounded-lg font-black text-slate-600"><?= $log['qty'] ?></span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-[10px] font-black uppercase text-emerald-500 italic">
                                        <i class="fas fa-check-circle mr-1"></i> Telah Diterima
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-slate-400 italic">
                                Belum ada data pengambilan barang yang terekam pada periode ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        aside,
        header {
            display: none !important;
        }

        body {
            background: white !important;
        }

        main {
            padding: 0 !important;
            width: 100% !important;
            margin: 0 !important;
        }

        .fade-in {
            transform: none !important;
            opacity: 1 !important;
        }
    }
</style>
<?= $this->endSection() ?>