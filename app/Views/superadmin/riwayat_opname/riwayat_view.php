<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter italic uppercase">
                Audit <span class="text-blue-600">History</span>
            </h3>
            <div class="flex items-center gap-3 mt-1">
                <span class="px-2.5 py-0.5 bg-slate-200 text-slate-600 text-[10px] font-black rounded-lg uppercase tracking-widest shadow-sm">
                    Stock Opname Logs
                </span>
                <p class="text-sm text-slate-500 font-medium italic">
                    Rekam jejak penyesuaian stok fisik gudang.
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[50px] border border-slate-200 overflow-hidden shadow-xl shadow-slate-200/40">
        <div class="p-10 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-list-ul text-xs"></i>
                </div>
                <h4 class="font-black text-slate-800 uppercase italic tracking-tighter">Log Transaksi Opname</h4>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/80 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b">
                    <tr>
                        <th class="px-10 py-6">Waktu Pelaksanaan</th>
                        <th class="px-10 py-6">Informasi Barang</th>
                        <th class="px-10 py-6 text-center">Data Sistem</th>
                        <th class="px-10 py-6 text-center">Fisik Riil</th>
                        <th class="px-10 py-6 text-center">Selisih</th>
                        <th class="px-10 py-6 text-right">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($history)): ?>
                        <?php foreach ($history as $h): ?>
                            <tr class="hover:bg-blue-50/30 transition-all group">
                                <td class="px-10 py-8">
                                    <p class="font-black text-slate-700 uppercase italic leading-none mb-1"><?= date('d/m/Y', strtotime($h['created_at'])) ?></p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest"><?= date('H:i', strtotime($h['created_at'])) ?> WIB</p>
                                </td>
                                <td class="px-10 py-8 text-sm">
                                    <p class="font-bold text-slate-800 italic group-hover:text-blue-600 transition-colors"><?= esc($h['nama_barang']) ?></p>
                                    <p class="text-[10px] font-black text-slate-400 uppercase">ID Inventory: #<?= $h['inventory_id'] ?></p>
                                </td>
                                <td class="px-10 py-8 text-center font-bold text-slate-400">
                                    <?= $h['stok_sistem'] ?> <span class="text-[9px]">Pack</span>
                                </td>
                                <td class="px-10 py-8 text-center font-black text-slate-900">
                                    <?= $h['stok_fisik'] ?> <span class="text-[9px]">Pack</span>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <?php if ($h['selisih'] == 0): ?>
                                        <span class="px-3 py-1 bg-slate-100 text-slate-400 text-[10px] font-black rounded-lg italic">MATCH</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 <?= $h['selisih'] < 0 ? 'bg-rose-50 text-rose-600' : 'bg-blue-50 text-blue-600' ?> text-[10px] font-black rounded-lg">
                                            <?= $h['selisih'] > 0 ? '+' : '' ?><?= $h['selisih'] ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <p class="text-xs italic text-slate-500 font-medium leading-relaxed">"<?= esc($h['keterangan']) ?: 'Tidak ada keterangan' ?>"</p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-10 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox text-5xl text-slate-100 mb-4"></i>
                                    <p class="text-slate-300 font-black italic tracking-widest uppercase text-xs">Belum ada aktivitas audit</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>