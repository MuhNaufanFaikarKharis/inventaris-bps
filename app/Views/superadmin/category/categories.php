<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-10 pb-10">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter italic uppercase leading-none">
                Master <span class="text-blue-600">Kategori</span>
            </h3>
            <div class="flex items-center gap-3 mt-2">
                <span class="px-2.5 py-0.5 bg-slate-800 text-white text-[9px] font-black rounded-lg uppercase tracking-widest shadow-sm">
                    Struktur Inventory
                </span>
                <p class="text-sm text-slate-500 font-medium italic">Pengelompokan barang untuk mempermudah audit stok.</p>
            </div>
        </div>

        <button onclick="openCategoryModal()" class="group flex items-center justify-center gap-3 px-8 py-4 bg-blue-600 rounded-[25px] text-[11px] font-black text-white hover:bg-slate-900 transition-all uppercase tracking-[0.2em] shadow-xl shadow-blue-200">
            <i class="fas fa-plus group-hover:rotate-90 transition-transform"></i> Tambah Kategori
        </button>
    </div>

    <div class="bg-white rounded-[50px] border border-slate-200 overflow-hidden shadow-xl shadow-slate-200/40">
        <div class="p-10 border-b border-slate-100 flex items-center gap-4 bg-slate-50/50">
            <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center shadow-lg"><i class="fas fa-tags text-xs"></i></div>
            <div>
                <h4 class="font-black text-slate-800 uppercase italic tracking-tighter">Grup Inventaris</h4>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Kategori Aktif</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/80 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b">
                    <tr>
                        <th class="px-10 py-6">Nama Kategori</th>
                        <th class="px-10 py-6">Deskripsi</th>
                        <th class="px-10 py-6 text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $c): ?>
                            <tr class="hover:bg-slate-50 transition-all group">
                                <td class="px-10 py-8">
                                    <p class="font-black text-slate-700 uppercase italic leading-none mb-1 group-hover:text-blue-600 transition-colors"><?= esc($c['nama_kategori']) ?></p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Kelas Inventory</p>
                                </td>
                                <td class="px-10 py-8 text-slate-500 text-sm italic font-medium">
                                    <?= esc($c['deskripsi'] ?: 'Tidak ada deskripsi.') ?>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <div class="flex justify-end gap-3">
                                        <button onclick='editCategory(<?= json_encode($c) ?>)' class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button onclick="confirmDeleteCategory(<?= $c['id'] ?>)" class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-10 py-24 text-center text-slate-300 font-black italic tracking-widest uppercase">Belum ada kategori</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalCategory" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[50px] w-full max-w-lg p-10 shadow-2xl fade-in border border-slate-100">
        <div class="flex justify-between items-center mb-10">
            <h3 id="modalTitle" class="text-xl font-black text-slate-800 uppercase italic tracking-tighter leading-none">Category Form</h3>
            <button onclick="closeCategoryModal()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>

        <form action="<?= base_url('superadmin/categories/save') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="cat_id">

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="cat_nama" required
                    class="w-full bg-slate-50 border-none rounded-[20px] px-6 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner" placeholder="Contoh: Alat Tulis Kantor">
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Deskripsi Singkat</label>
                <textarea name="deskripsi" id="cat_desc" rows="3"
                    class="w-full bg-slate-50 border-none rounded-[20px] px-6 py-4 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner" placeholder="Berikan keterangan kategori..."></textarea>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="button" onclick="closeCategoryModal()" class="flex-1 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600">Batal</button>
                <button type="submit" class="flex-[2] bg-slate-900 text-white py-5 rounded-[25px] font-black text-[10px] uppercase tracking-[0.2em] shadow-xl hover:bg-blue-600 transition-all active:scale-95 shadow-slate-200">
                    Simpan Kategori <i class="fas fa-save ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCategoryModal() {
        document.getElementById('modalTitle').innerText = 'Buat Kategori Baru';
        document.getElementById('cat_id').value = '';
        document.getElementById('cat_nama').value = '';
        document.getElementById('cat_desc').value = '';
        document.getElementById('modalCategory').classList.remove('hidden');
    }

    function editCategory(data) {
        document.getElementById('modalTitle').innerText = 'Edit Kategori';
        document.getElementById('cat_id').value = data.id;
        document.getElementById('cat_nama').value = data.nama_kategori;
        document.getElementById('cat_desc').value = data.deskripsi;
        document.getElementById('modalCategory').classList.remove('hidden');
    }

    function closeCategoryModal() {
        document.getElementById('modalCategory').classList.add('hidden');
    }

    function confirmDeleteCategory(id) {
        Swal.fire({
            title: 'HAPUS KATEGORI?',
            text: "Barang dengan kategori ini mungkin akan menjadi 'Uncategorized'.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'KONFIRMASI HAPUS',
            cancelButtonText: 'BATAL',
            customClass: {
                popup: 'rounded-[40px] p-10',
                confirmButton: 'rounded-xl font-bold px-8 py-3 text-xs',
                cancelButton: 'rounded-xl font-bold px-8 py-3 text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) window.location.href = "<?= base_url('superadmin/categories/delete') ?>/" + id;
        });
    }
</script>
<?= $this->endSection() ?>