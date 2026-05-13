<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight uppercase italic">Katalog Stok Barang</h3>
            <p class="text-sm text-slate-500 font-medium">Lihat ketersediaan barang sebelum melakukan permintaan.</p>
        </div>

        <div class="relative w-full md:w-80">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" id="searchStok" onkeyup="searchTable()" placeholder="Cari nama barang..."
                class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-5 py-3 text-sm focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
        </div>
    </div>

    <div class="bg-white rounded-[40px] border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="stokTable">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Nama Barang</th>
                        <th class="px-8 py-5">Kategori</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Sisa Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    <?php if (!empty($inventory)): ?>
                        <?php foreach ($inventory as $item): ?>
                            <tr class="hover:bg-slate-50/50 transition-all border-l-4 border-transparent hover:border-blue-500">
                                <td class="px-8 py-6 font-bold text-slate-700 uppercase italic">
                                    <?= esc($item['nama_barang']) ?>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase kategori-label">
                                        <?= esc($item['nama_kategori'] ?? 'Tanpa Kategori') ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php if ($item['stok'] > 10): ?>
                                        <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase">Tersedia</span>
                                    <?php elseif ($item['stok'] > 0): ?>
                                        <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase">Terbatas</span>
                                    <?php else: ?>
                                        <span class="bg-rose-100 text-rose-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase">Habis</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-right font-black text-lg <?= $item['stok'] <= 5 ? 'text-rose-500' : 'text-slate-800' ?>">
                                    <?= $item['stok'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-slate-400 italic">Data stok barang kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function searchTable() {
        let input = document.getElementById("searchStok");
        let filter = input.value.toUpperCase();
        let table = document.getElementById("stokTable");
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                let txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<?= $this->endSection() ?>