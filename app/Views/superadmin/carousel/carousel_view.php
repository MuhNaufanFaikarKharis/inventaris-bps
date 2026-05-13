<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="fade-in space-y-8">
    <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-200">
        <h3 class="text-xl font-black text-slate-800 uppercase italic mb-6">Unggah Foto Carousel Baru</h3>

        <form action="<?= base_url('superadmin/save_carousel') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Judul Gambar / Caption</label>
                <input type="text" name="title" required placeholder="Contoh: Gedung BPS Kota Pekalongan"
                    class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
            </div>

            <div class="flex items-center justify-center w-full">
                <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 border-dashed rounded-[32px] cursor-pointer bg-slate-50 hover:bg-slate-100 transition-all">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3"></i>
                        <p class="mb-2 text-sm text-slate-500 font-bold">Klik untuk memilih foto</p>
                        <p class="text-xs text-slate-400">Rekomendasi: 1200x400px (Max. 2MB)</p>
                    </div>
                    <input type="file" name="image" class="hidden" required />
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold shadow-lg hover:bg-blue-700 transition-all active:scale-95">
                <i class="fas fa-save mr-2"></i> SIMPAN FOTO CAROUSEL
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php if (!empty($slides)): foreach ($slides as $s): ?>
                <div class="group relative bg-white p-3 rounded-[32px] shadow-sm border border-slate-200">
                    <div class="aspect-video rounded-[24px] overflow-hidden bg-slate-100">
                        <img src="<?= base_url('uploads/carousel/' . $s->image_path) ?>"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="mt-4 flex justify-between items-start px-2">
                        <div>
                            <p class="text-xs font-black text-slate-700 uppercase leading-tight"><?= esc($s->title) ?></p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase italic mt-1"><?= date('d M Y', strtotime($s->created_at)) ?></p>
                        </div>
                        <button onclick="confirmDeleteCarousel(<?= $s->id ?>)" class="text-rose-500 hover:bg-rose-50 p-2 rounded-lg transition-colors">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>

                    <div class="absolute top-6 left-6">
                        <span class="bg-emerald-500 text-white text-[8px] font-black px-3 py-1 rounded-full shadow-lg uppercase">Aktif</span>
                    </div>
                </div>
            <?php endforeach;
        else: ?>
            <div class="col-span-3 py-20 text-center text-slate-400 italic">
                <i class="fas fa-images text-4xl mb-3 block opacity-20"></i>
                Belum ada foto yang diunggah.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmDeleteCarousel(id) {
        Swal.fire({
            title: 'Hapus Foto?',
            text: "Gambar ini tidak akan muncul di halaman depan publik.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'bps-modern-popup',
                confirmButton: 'btn-confirm',
                cancelButton: 'btn-cancel'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('superadmin/delete_carousel') ?>/" + id;
            }
        });
    }
</script>
<?= $this->endSection() ?>