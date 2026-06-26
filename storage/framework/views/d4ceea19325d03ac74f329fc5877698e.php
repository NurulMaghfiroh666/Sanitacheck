<?php $__env->startSection('title', 'Dashboard Petugas | SanitaCheck'); ?>

<?php $__env->startSection('content'); ?>
<div class="text-center py-4">
    <div class="mb-3">
        <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
    </div>
    <h4 class="fw-bold text-dark">Selamat Datang, <?php echo e(Auth::user()->name); ?>!</h4>
    <p class="text-secondary">Panel monitoring inspeksi sanitasi kamu.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body text-center p-3">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-success bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px;">
                    <i class="bi bi-calendar2-check text-success fs-4"></i>
                </div>
                <div class="fw-bold fs-3 text-dark"><?php echo e($jadwalHariIni); ?></div>
                <div class="text-secondary small">Jadwal Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body text-center p-3">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-primary bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px;">
                    <i class="bi bi-clipboard-check text-primary fs-4"></i>
                </div>
                <div class="fw-bold fs-3 text-dark"><?php echo e($inspeksiSelesai); ?></div>
                <div class="text-secondary small">Total Inspeksi</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3" style="border-radius: 15px;">
    <div class="card-body p-3">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-calendar3 text-success me-2"></i>Jadwal Inspeksi Terbaru</h6>
        <?php if($jadwalTerbaru->isEmpty()): ?>
        <div class="text-center py-3">
            <p class="text-secondary mb-0 small">Belum ada jadwal inspeksi.</p>
        </div>
        <?php else: ?>
        <div class="d-flex flex-column gap-2">
            <?php $__currentLoopData = $jadwalTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border rounded p-3" style="border-radius: 10px;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold text-dark"><?php echo e($jadwal->jenis_tugas); ?></div>
                        <div class="text-secondary small">
                            <i class="bi bi-building me-1"></i><?php echo e($jadwal->fasilitas->nama_fasilitas ?? '-'); ?>

                        </div>
                    </div>
                    <?php if($jadwal->status == 'Menunggu'): ?>
                    <span class="badge bg-secondary" style="font-size: 0.7rem;">Menunggu</span>
                    <?php elseif($jadwal->status == 'Berlangsung'): ?>
                    <span class="badge bg-primary" style="font-size: 0.7rem;">Berlangsung</span>
                    <?php elseif($jadwal->status == 'Selesai'): ?>
                    <span class="badge bg-success" style="font-size: 0.7rem;">Selesai</span>
                    <?php endif; ?>
                </div>
                <div class="d-flex justify-content-between small text-secondary">
                    <span><i class="bi bi-calendar me-1"></i><?php echo e($jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-'); ?> - <?php echo e($jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-'); ?></span>
                    <a href="<?php echo e(route('petugas.jadwal')); ?>" class="text-success fw-semibold">Lihat Detail</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-3 text-center">
        <p class="text-secondary mb-0 small">
            <i class="bi bi-info-circle me-1"></i> Pilih menu <strong>Jadwal</strong> di bawah untuk melihat semua jadwal inspeksi.
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/petugas/dashboard.blade.php ENDPATH**/ ?>