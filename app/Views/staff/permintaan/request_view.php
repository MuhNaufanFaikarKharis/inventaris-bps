<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight uppercase italic">Status Permintaan Barang</h3>
            <p class="text-sm text-slate-500 font-medium">Pantau status pengajuan ATK Anda di bawah ini.</p>
        </div>
        <button onclick="openRequestModal()" class="bg-blue-600 text-white px-8 py-4 rounded-[24px] shadow-lg shadow-blue-200 font-bold text-sm hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-3 group">
            <i class="fas fa-plus-circle text-lg group-hover:rotate-90 transition-transform duration-300"></i> BUAT PERMINTAAN BARU
        </button>
    </div>

    <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 bg-slate-50/50">
            <h4 class="font-bold text-slate-800 uppercase tracking-tighter italic text-sm">Riwayat Pengajuan Saya</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Informasi Barang</th>
                        <th class="px-8 py-5 text-center">Jumlah</th>
                        <th class="px-8 py-5">Tanggal</th>
                        <th class="px-8 py-5 text-right">Status</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $r): ?>
                            <tr class="hover:bg-blue-50/30 transition-all">
                                <td class="px-8 py-6">
                                    <p class="font-bold text-slate-700"><?= esc($r['nama_barang']) ?></p>
                                    <p class="text-xs text-slate-400 italic mt-1">"<?= esc($r['alasan']) ?>"</p>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="bg-slate-100 px-3 py-1 rounded-lg font-black text-slate-600"><?= $r['qty'] ?></span>
                                </td>
                                <td class="px-8 py-6 text-slate-500 text-xs font-medium">
                                    <?= date('d M Y', strtotime($r['created_at'])) ?>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <?php if ($r['status'] === 'pending'): ?>
                                        <span class="bg-amber-100 text-amber-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase italic">Menunggu</span>
                                    <?php elseif ($r['status'] === 'disetujui'): ?>
                                        <span class="bg-emerald-100 text-emerald-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase italic">Disetujui</span>
                                    <?php else: ?>
                                        <span class="bg-rose-100 text-rose-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase italic">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php if ($r['status'] === 'disetujui'): ?>
                                        <?php if (empty($r['bukti_foto'])): ?>
                                            <button onclick="openConfirmModal(<?= $r['id'] ?>)" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-[10px] font-bold hover:bg-indigo-700 transition-all flex items-center gap-2 mx-auto shadow-md">
                                                <i class="fas fa-camera"></i> KONFIRMASI
                                            </button>
                                        <?php else: ?>
                                            <a href="<?= base_url('uploads/bukti_terima/' . $r['bukti_foto']) ?>" target="_blank" class="inline-flex items-center gap-1.5 text-emerald-600 font-bold text-[10px] hover:underline">
                                                <i class="fas fa-check-circle"></i> LIHAT BUKTI
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-slate-300 text-[10px] italic">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox text-slate-100 text-6xl mb-4"></i>
                                    <p class="text-slate-400 italic">Belum ada riwayat permintaan.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="requestModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-[48px] w-full max-w-lg p-2 shadow-2xl fade-in overflow-hidden relative">
        <div class="bg-white p-10">
            <button onclick="closeRequestModal()" class="absolute top-8 right-8 text-slate-300 hover:text-rose-500 transition-colors">
                <i class="fas fa-times-circle text-2xl"></i>
            </button>

            <div class="mb-10">
                <div class="bg-blue-600 w-12 h-12 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg shadow-blue-200">
                    <i class="fas fa-paper-plane text-lg"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Form Permintaan</h3>
                <p class="text-sm text-slate-400 font-medium">Pastikan stok tersedia sebelum mengajukan.</p>
            </div>

            <form action="<?= base_url('staff/save_request') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Barang</label>
                    <div class="relative group">
                        <i class="fas fa-box absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                        <select name="nama_barang" required class="w-full bg-slate-50 border border-slate-100 rounded-[24px] pl-14 pr-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih item dari gudang...</option>
                            <?php foreach ($inventory as $i): ?>
                                <option value="<?= $i['nama_barang'] ?>"><?= $i['nama_barang'] ?> (Tersedia: <?= $i['stok'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Jumlah</label>
                        <input type="number" name="qty" required min="1" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-[24px] px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Keperluan</label>
                        <input type="text" name="alasan" required placeholder="Tulis alasan..." class="w-full bg-slate-50 border border-slate-100 rounded-[24px] px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    </div>
                </div>

                <div class="pt-6 flex flex-col md:flex-row gap-3">
                    <button type="button" onclick="closeRequestModal()" class="flex-1 py-4 text-slate-400 font-bold text-sm">BATAL</button>
                    <button type="submit" class="flex-[2] bg-blue-600 text-white py-4 rounded-[24px] font-bold text-sm hover:bg-blue-700 transition-all shadow-xl shadow-blue-200">
                        KIRIM PERMINTAAN <i class="fas fa-arrow-right text-xs ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="confirmModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[70] flex items-center justify-center p-4">
    <div class="bg-white rounded-[40px] w-full max-w-sm p-8 shadow-2xl fade-in relative">
        <button onclick="closeConfirmModal()" class="absolute top-6 right-6 text-slate-300 hover:text-rose-500 transition-colors">
            <i class="fas fa-times-circle text-2xl"></i>
        </button>

        <div class="text-center mb-6">
            <div class="bg-indigo-100 w-12 h-12 rounded-2xl flex items-center justify-center text-indigo-600 mx-auto mb-4">
                <i class="fas fa-camera text-xl"></i>
            </div>
            <h3 class="text-lg font-black text-slate-800 uppercase italic tracking-tighter">Bukti Terima</h3>
            <p class="text-xs text-slate-400 font-medium">Upload foto barang yang Anda terima.</p>
        </div>

        <form id="confirmForm" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <div class="group border-2 border-dashed border-slate-200 rounded-[24px] p-6 text-center hover:border-indigo-400 transition-all">
                <input type="file" name="bukti_foto" id="bukti_foto" required class="hidden" accept="image/*" onchange="previewText(this)">
                <label for="bukti_foto" class="cursor-pointer block">
                    <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 group-hover:text-indigo-500 mb-2"></i>
                    <p id="fileLabel" class="text-[10px] font-black text-slate-500 uppercase italic">Klik Pilih Foto</p>
                </label>
            </div>
            <button type="submit" class="w-full bg-slate-800 text-white py-4 rounded-[20px] font-bold text-xs hover:bg-black transition-all">
                KONFIRMASI PENERIMAAN
            </button>
        </form>
    </div>
</div>

<script>
    // Modal Permintaan Baru
    function openRequestModal() {
        document.getElementById('requestModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRequestModal() {
        document.getElementById('requestModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Modal Konfirmasi Terima
    function openConfirmModal(id) {
        const form = document.getElementById('confirmForm');
        form.action = "<?= base_url('staff/konfirmasi_terima/') ?>/" + id;
        document.getElementById('confirmModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Preview Nama File
    function previewText(input) {
        if (input.files && input.files[0]) {
            document.getElementById('fileLabel').innerText = "Terpilih: " + input.files[0].name;
            document.getElementById('fileLabel').className = "text-[10px] font-black text-indigo-600 uppercase italic";
        }
    }

    // Close on Escape
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRequestModal();
            closeConfirmModal();
        }
    });
</script>
<?= $this->endSection() ?>