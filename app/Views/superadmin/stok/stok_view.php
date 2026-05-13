<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight uppercase italic">Daftar Stok Inventaris</h3>
            <p class="text-sm text-slate-500 font-medium">Registrasi barang baru dan pantau aset ATK BPS.</p>
        </div>
        <div class="flex gap-3">
            <?php if (session()->get('role') === 'super admin'): ?>
                <button onclick="openModalBarang()" class="bg-blue-600 text-white px-6 py-3 rounded-2xl shadow-lg font-bold text-sm hover:bg-black transition-all active:scale-95">
                    <i class="fas fa-plus mr-2"></i> Tambah Barang Baru
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-[40px] border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between gap-4">
            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="searchInput" placeholder="Cari nama barang..." class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>

            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filter Kategori:</span>
                <select id="categoryFilter" class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold text-slate-600 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= esc($cat['nama_kategori']) ?>"><?= esc($cat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left" id="stokTable">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Informasi Barang</th>
                        <th class="px-8 py-5">Kategori</th>
                        <th class="px-8 py-5 text-center">Jumlah Stok</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (!empty($inventory)): ?>
                        <?php foreach ($inventory as $i): ?>
                            <tr class="hover:bg-slate-50/80 transition-all row-item">
                                <td class="px-8 py-6">
                                    <p class="font-bold text-slate-700 nama-barang uppercase italic"><?= esc($i['nama_barang']) ?></p>
                                    <p class="text-[10px] text-slate-400 font-medium tracking-widest uppercase">ID: #BPS-<?= $i['id'] ?></p>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase kategori-label">
                                        <?= esc($i['nama_kategori'] ?? 'Tanpa Kategori') ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-lg font-black text-slate-800"><?= $i['stok'] ?></span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php if ($i['stok'] <= 0): ?>
                                        <span class="text-rose-500 font-black text-[9px] uppercase bg-rose-50 px-3 py-1 rounded-full italic tracking-tighter">Habis</span>
                                    <?php elseif ($i['stok'] < 10): ?>
                                        <span class="text-amber-500 font-black text-[9px] uppercase bg-amber-50 px-3 py-1 rounded-full italic tracking-tighter">Menipis</span>
                                    <?php else: ?>
                                        <span class="text-emerald-500 font-black text-[9px] uppercase bg-emerald-50 px-3 py-1 rounded-full italic tracking-tighter">Tersedia</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick='editBarang(<?= json_encode($i) ?>)' class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button onclick="confirmDeleteStok(<?= $i['id'] ?>)" class="w-9 h-9 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-slate-400 italic font-medium uppercase tracking-[0.2em]">Database Stok Kosong</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalBarangBaru" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[40px] w-full max-w-md p-10 shadow-2xl fade-in border border-white/20">
        <div class="flex justify-between items-center mb-8">
            <h3 id="modalTitle" class="text-xl font-black text-slate-800 uppercase tracking-tighter italic">Registrasi Aset Baru</h3>
            <button onclick="closeModalBarang()" class="text-slate-400 hover:text-slate-900 transition-colors"><i class="fas fa-times"></i></button>
        </div>

        <form action="<?= base_url('superadmin/save_tambah') ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="item_id">

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Nama Barang</label>
                <input type="text" name="nama" id="item_nama" required placeholder="Contoh: Kertas HVS A4 80gr"
                    class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Pilih Kategori</label>
                <select name="category_id" id="item_category" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner cursor-pointer">
                    <option value="">-- PILIH KATEGORI --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= strtoupper($cat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="stokInputWrapper">
                <label id="stokLabel" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Stok Awal</label>
                <input type="number" name="qty" id="item_qty" required min="0" value="1"
                    class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Keterangan</label>
                <textarea name="keterangan" id="item_keterangan" rows="2" placeholder="Detail pengadaan barang..."
                    class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner transition-all"></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModalBarang()"
                    class="flex-1 py-4 font-black text-slate-400 text-[10px] uppercase tracking-[0.2em] hover:text-slate-800 transition-all">Batal</button>
                <button type="submit"
                    class="flex-[2] py-4 bg-slate-900 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-slate-200 hover:bg-blue-600 transition-all active:scale-95">
                    Simpan Perubahan <i class="fas fa-database ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi Pencarian & Filter
    function filterTable() {
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const categoryQuery = document.getElementById('categoryFilter').value.toLowerCase();
        const rows = document.querySelectorAll("#stokTable tbody .row-item");

        rows.forEach(row => {
            const namaText = row.querySelector(".nama-barang").textContent.toLowerCase();
            const kategoriText = row.querySelector(".kategori-label").textContent.toLowerCase();

            const matchesSearch = namaText.includes(searchQuery);
            const matchesCategory = categoryQuery === "" || kategoriText.trim() === categoryQuery.trim();

            row.style.display = (matchesSearch && matchesCategory) ? "" : "none";
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('categoryFilter').addEventListener('change', filterTable);

    // Modal Control
    function openModalBarang() {
        document.getElementById('modalTitle').innerText = 'Registrasi Aset Baru';
        document.getElementById('item_id').value = '';
        document.getElementById('item_nama').value = '';
        document.getElementById('item_category').value = '';
        document.getElementById('item_qty').value = '1';
        document.getElementById('stokLabel').innerText = 'Stok Awal';
        document.getElementById('item_keterangan').value = '';
        document.getElementById('modalBarangBaru').classList.remove('hidden');
    }

    // FUNGSI EDIT
    function editBarang(data) {
        document.getElementById('modalTitle').innerText = 'Edit Informasi Aset';
        document.getElementById('item_id').value = data.id;
        document.getElementById('item_nama').value = data.nama_barang;
        document.getElementById('item_category').value = data.category_id;
        document.getElementById('item_qty').value = data.stok;
        document.getElementById('stokLabel').innerText = 'Update Total Stok';
        document.getElementById('item_keterangan').value = 'Update informasi barang';
        document.getElementById('modalBarangBaru').classList.remove('hidden');
    }

    function closeModalBarang() {
        document.getElementById('modalBarangBaru').classList.add('hidden');
    }

    // FUNGSI HAPUS (SWEETALERT)
    function confirmDeleteStok(id) {
        Swal.fire({
            title: 'HAPUS BARANG?',
            text: "Seluruh data stok ini akan dihapus permanen dari database!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            customClass: {
                popup: 'rounded-[40px] p-10'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('superadmin/stok/delete') ?>/" + id;
            }
        });
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('modalBarangBaru')) closeModalBarang();
    }
</script>
<?= $this->endSection() ?>