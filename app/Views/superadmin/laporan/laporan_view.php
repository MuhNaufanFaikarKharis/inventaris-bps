<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-6 pb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
        <div>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight uppercase italic">Laporan Pusat BPS</h3>
            <p class="text-sm text-slate-500 font-medium">Rekapitulasi data operasional inventaris.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-slate-800 text-white px-6 py-3 rounded-2xl shadow-lg font-bold text-sm hover:bg-black transition-all">
                <i class="fas fa-print mr-2"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2 no-print">
        <a href="<?= base_url('superadmin/laporan?kategori=mutasi') ?>"
            class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all <?= $kategori == 'mutasi' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-400 border border-slate-200 hover:bg-slate-50' ?>">
            Semua Mutasi
        </a>
        <a href="<?= base_url('superadmin/laporan?kategori=permintaan') ?>"
            class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all <?= $kategori == 'permintaan' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-slate-400 border border-slate-200 hover:bg-slate-50' ?>">
            Permintaan/Validasi
        </a>
        <a href="<?= base_url('superadmin/laporan?kategori=audit') ?>"
            class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all <?= $kategori == 'audit' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-400 border border-slate-200 hover:bg-slate-50' ?>">
            Audit/Stock Opname
        </a>
    </div>

    <div class="bg-white p-6 rounded-[30px] border border-slate-200 shadow-sm no-print">
        <form action="<?= base_url('superadmin/laporan') ?>" method="GET" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="kategori" value="<?= $kategori ?>">

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase ml-2 text-slate-400">Dari Tanggal</label>
                <input type="date" name="tgl_awal" value="<?= $tgl_awal ?>" class="block w-full px-4 py-2 rounded-xl border-slate-200 text-sm focus:ring-blue-500">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase ml-2 text-slate-400">Sampai Tanggal</label>
                <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?>" class="block w-full px-4 py-2 rounded-xl border-slate-200 text-sm focus:ring-blue-500">
            </div>

            <?php if ($kategori == 'mutasi'): ?>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase ml-2 text-slate-400">Tipe</label>
                    <select name="tipe" class="block w-full px-4 py-2 rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        <option value="">Semua</option>
                        <option value="Masuk" <?= $tipe == 'Masuk' ? 'selected' : '' ?>>Masuk</option>
                        <option value="Keluar" <?= $tipe == 'Keluar' ? 'selected' : '' ?>>Keluar</option>
                    </select>
                </div>
            <?php endif; ?>

            <button type="submit" class="bg-blue-50 text-blue-600 px-6 py-2 rounded-xl font-bold text-sm hover:bg-blue-600 hover:text-white transition-all">
                <i class="fas fa-filter mr-2"></i> Terapkan Filter
            </button>
            <a href="<?= base_url('superadmin/laporan?kategori=' . $kategori) ?>" class="text-slate-400 text-sm mb-2 hover:text-rose-500 underline">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-[40px] border border-slate-200 overflow-hidden shadow-sm print:border-none print:shadow-none">

        <div class="hidden print:block p-8 border-b-4 border-double border-slate-900 mb-6">
            <div class="flex items-center gap-6">
                <img src="<?= base_url('logo/Logo Badan Pusat Statistik (BPS) [RiderGalau].png') ?>" class="h-16 w-auto" alt="Logo BPS">
                <div class="flex-1">
                    <h1 class="text-xl font-black uppercase tracking-tighter leading-none">Badan Pusat Statistik</h1>
                    <h2 class="text-lg font-bold uppercase tracking-tight">kota Pekalongan</h2>
                    <p class="text-[10px] text-slate-600 mt-1 italic leading-tight">
                        Jl. Singosari Pekalongan 51111, Telp. (0285) 423504, Email : bps3375@bps.go.id
                    </p>
                </div>
                <div class="text-right border-l pl-6 border-slate-200">
                    <h3 class="text-sm font-black uppercase italic text-slate-800">Laporan <?= ucfirst($kategori) ?></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">Periode: <?= $tgl_awal ?: 'Awal' ?> - <?= $tgl_akhir ?: date('d/m/Y') ?></p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left print:text-[11px]">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest font-black border-b border-slate-100 print:bg-slate-200">
                    <tr>
                        <th class="px-8 py-5">Waktu</th>
                        <?php if ($kategori == 'audit'): ?>
                            <th class="px-8 py-5">Barang</th>
                            <th class="px-8 py-5 text-center">Sistem</th>
                            <th class="px-8 py-5 text-center">Fisik</th>
                            <th class="px-8 py-5 text-center">Selisih</th>
                        <?php elseif ($kategori == 'permintaan'): ?>
                            <th class="px-8 py-5">Barang</th>
                            <th class="px-8 py-5">Pemohon</th>
                            <th class="px-8 py-5 text-center">Qty</th>
                            <th class="px-8 py-5 text-center">Status</th>
                        <?php else: ?>
                            <th class="px-8 py-5">Nama Barang</th>
                            <th class="px-8 py-5 text-center">Tipe</th>
                            <th class="px-8 py-5 text-center">Jumlah</th>
                            <th class="px-8 py-5 text-right">Oleh</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $row): ?>
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="px-8 py-6">
                                    <span class="block font-bold text-slate-700"><?= date('d/m/Y', strtotime($row['created_at'])) ?></span>
                                    <span class="text-[10px] text-slate-400"><?= date('H:i', strtotime($row['created_at'])) ?> WIB</span>
                                </td>

                                <?php if ($kategori == 'audit'): ?>
                                    <td class="px-8 py-6 font-black text-slate-800 italic uppercase"><?= esc($row['nama_barang']) ?></td>
                                    <td class="px-8 py-6 text-center font-bold"><?= $row['stok_sistem'] ?></td>
                                    <td class="px-8 py-6 text-center font-bold text-blue-600"><?= $row['stok_fisik'] ?></td>
                                    <td class="px-8 py-6 text-center font-black <?= $row['selisih'] < 0 ? 'text-rose-600' : 'text-emerald-600' ?>">
                                        <?= $row['selisih'] ?>
                                    </td>
                                <?php elseif ($kategori == 'permintaan'): ?>
                                    <td class="px-8 py-6 font-black text-slate-800 italic uppercase"><?= esc($row['nama_barang']) ?></td>
                                    <td class="px-8 py-6 font-bold text-slate-700"><?= esc($row['nama_pemohon']) ?></td>
                                    <td class="px-8 py-6 text-center font-black"><?= $row['qty'] ?></td>
                                    <td class="px-8 py-6 text-center text-[10px] font-black uppercase">
                                        <?php
                                        // Kita paksa jadi huruf kecil semua untuk pengecekan agar akurat
                                        $status_cek = strtolower(trim($row['status']));

                                        if ($status_cek == 'disetujui') {
                                            $class = 'bg-emerald-100 text-emerald-600';
                                        } elseif ($status_cek == 'ditolak') {
                                            $class = 'bg-rose-100 text-rose-600';
                                        } else {
                                            $class = 'bg-amber-100 text-amber-600'; // Untuk Pending/Menunggu
                                        }
                                        ?>
                                        <span class="px-3 py-1 rounded-full <?= $class ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                <?php else: ?>
                                    <td class="px-8 py-6 font-black text-slate-800 italic uppercase"><?= esc($row['nama_barang']) ?></td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase <?= $row['tipe'] === 'Masuk' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' ?>">
                                            <?= $row['tipe'] ?>
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-center font-black <?= $row['tipe'] === 'Masuk' ? 'text-emerald-600' : 'text-rose-600' ?>">
                                        <?= $row['tipe'] === 'Masuk' ? '+' : '-' ?><?= $row['qty'] ?>
                                    </td>
                                    <td class="px-8 py-6 text-right font-bold text-slate-700"><?= esc($row['nama_pelaku'] ?? $row['nama_petugas'] ?? 'Admin') ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <i class="fas fa-search text-slate-200 text-4xl mb-4 block"></i>
                                <p class="text-slate-400 italic">Data <?= $kategori ?> tidak ditemukan.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="hidden print:grid grid-cols-2 gap-20 p-12 mt-10">
            <div class="text-center"></div>
            <div class="text-center border-t border-slate-900 pt-4">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-16 italic">Petugas Inventaris BPS</p>
                <p class="text-xs font-black uppercase underline"><?= esc($nama_ttd) ?></p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {

        .no-print,
        aside,
        nav,
        button,
        form {
            display: none !important;
        }

        @page {
            size: A4;
            margin: 1.5cm;
        }

        body {
            background: white !important;
        }

        main {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
    }
</style>
<?= $this->endSection() ?>