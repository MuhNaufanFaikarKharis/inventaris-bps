<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight uppercase italic">Validasi Permintaan Staff</h3>
            <p class="text-sm text-slate-500 font-medium">Setujui atau tolak permintaan barang dari pegawai BPS.</p>
        </div>

        <div class="flex gap-4">
            <div class="bg-amber-50 border border-amber-100 px-4 py-2 rounded-2xl text-center">
                <p class="text-[10px] font-bold text-amber-600 uppercase">Perlu Tindakan</p>
                <p class="text-lg font-black text-amber-700"><?= count(array_filter($requests, fn($r) => $r['status'] === 'pending')) ?></p>
            </div>
        </div>
    </div>

    <div class="flex gap-2">
        <button onclick="filterStatus('all')" class="tab-btn active px-6 py-2 rounded-full text-xs font-bold transition-all">Semua</button>
        <button onclick="filterStatus('pending')" class="tab-btn px-6 py-2 rounded-full text-xs font-bold transition-all bg-white border border-slate-200 text-slate-400">Pending</button>
        <button onclick="filterStatus('disetujui')" class="tab-btn px-6 py-2 rounded-full text-xs font-bold transition-all bg-white border border-slate-200 text-slate-400">Disetujui</button>
        <button onclick="filterStatus('ditolak')" class="tab-btn px-6 py-2 rounded-full text-xs font-bold transition-all bg-white border border-slate-200 text-slate-400 hover:text-rose-500">Ditolak</button>
    </div>

    <div class="bg-white rounded-[40px] border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="requestTable">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Info Pemohon</th>
                        <th class="px-8 py-5">Barang & Qty</th>
                        <th class="px-8 py-5">Alasan Keperluan</th>
                        <th class="px-8 py-5 text-center">Bukti</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $r): ?>
                            <tr class="row-request hover:bg-slate-50/50 transition-all" data-status="<?= $r['status'] ?>">
                                <td class="px-8 py-6">
                                    <p class="font-bold text-slate-700"><?= esc($r['nama_pemohon']) ?></p>
                                    <p class="text-[10px] text-slate-400"><?= date('d M Y, H:i', strtotime($r['created_at'])) ?></p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-bold text-blue-600"><?= esc($r['nama_barang']) ?></p>
                                    <p class="text-xs text-slate-500">Jumlah: <span class="font-black"><?= $r['qty'] ?></span></p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-slate-500 italic text-xs max-w-xs truncate" title="<?= esc($r['alasan']) ?>">
                                        "<?= esc($r['alasan']) ?>"
                                    </p>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php if (!empty($r['bukti_foto'])): ?>
                                        <img src="<?= base_url('uploads/bukti_terima/' . $r['bukti_foto']) ?>"
                                            class="w-10 h-10 object-cover rounded-xl mx-auto shadow-sm border border-slate-100 cursor-zoom-in"
                                            onclick="viewImage('<?= base_url('uploads/bukti_terima/' . $r['bukti_foto']) ?>')">
                                    <?php else: ?>
                                        <i class="fas fa-camera text-slate-200"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase
                        <?= $r['status'] === 'pending' ? 'bg-amber-100 text-amber-600' : ($r['status'] === 'disetujui' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600') ?>">
                                        <?= $r['status'] ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <?php if ($r['status'] === 'pending'): ?>
                                        <div class="flex justify-end gap-2">
                                            <button onclick="validaSi(<?= $r['id'] ?>, 'ditolak')" class="p-3 bg-rose-50 text-rose-500 rounded-2xl hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button onclick="validaSi(<?= $r['id'] ?>, 'disetujui')" class="p-3 bg-emerald-50 text-emerald-500 rounded-2xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">Selesai</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-slate-400 italic">Belum ada riwayat permintaan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="imageModal" class="hidden fixed inset-0 bg-slate-900/90 backdrop-blur-md z-[100] flex items-center justify-center p-4 transition-all" onclick="closeImageModal()">
    <div class="max-w-2xl w-full fade-in" onclick="event.stopPropagation()">
        <div class="relative">
            <img id="imgZoom" src="" class="w-full h-auto rounded-[40px] shadow-2xl border-4 border-white/20">
            <button onclick="closeImageModal()" class="absolute -top-4 -right-4 w-12 h-12 bg-white text-slate-900 rounded-full flex items-center justify-center shadow-xl hover:bg-rose-500 hover:text-white transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-white text-center mt-6 font-black text-[10px] uppercase tracking-[0.3em] italic opacity-50">Klik di luar gambar untuk menutup</p>
    </div>
</div>

<style>
    .tab-btn.active {
        background-color: #1e40af;
        color: white;
        border-color: #1e40af;
        box-shadow: 0 4px 6px -1px rgba(30, 64, 175, 0.2);
    }
</style>

<script>
    // FUNGSI ZOOM FOTO
    function viewImage(src) {
        document.getElementById('imgZoom').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // FUNGSI FILTER STATUS (Updated for 'ditolak')
    function filterStatus(status) {
        const buttons = document.querySelectorAll('.tab-btn');

        // 1. Reset semua style tombol ke default
        buttons.forEach(btn => {
            btn.classList.remove('active', 'bg-blue-900', 'bg-rose-600', 'bg-emerald-600', 'bg-amber-500', 'text-white');
            btn.classList.add('bg-white', 'text-slate-400');
        });

        // 2. Beri warna spesifik pada tombol yang aktif berdasarkan statusnya
        const activeBtn = event.currentTarget;
        activeBtn.classList.add('active', 'text-white');
        activeBtn.classList.remove('bg-white', 'text-slate-400');

        if (status === 'all') activeBtn.classList.add('bg-blue-900');
        else if (status === 'pending') activeBtn.classList.add('bg-amber-500');
        else if (status === 'disetujui') activeBtn.classList.add('bg-emerald-600');
        else if (status === 'ditolak') activeBtn.classList.add('bg-rose-600');

        // 3. Logic Filter Tabel
        const rows = document.querySelectorAll('.row-request');
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status').trim().toLowerCase();
            if (status === 'all' || rowStatus === status) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // FUNGSI KONFIRMASI (SweetAlert2)
    function validaSi(id, status) {
        const isApprove = status === 'disetujui';
        const textStatus = isApprove ?
            'Menyetujui permintaan ini akan otomatis memotong stok barang.' :
            'Permintaan ini akan ditolak dan tidak akan mengurangi stok.';

        const color = isApprove ? '#10b981' : '#f43f5e';

        Swal.fire({
            title: 'Konfirmasi ' + status.toUpperCase() + '?',
            text: textStatus,
            icon: isApprove ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: color,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[40px]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('superadmin/update_status') ?>/" + id + "/" + status;
            }
        });
    }

    // Menutup modal dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") closeImageModal();
    });
</script>
<?= $this->endSection() ?>