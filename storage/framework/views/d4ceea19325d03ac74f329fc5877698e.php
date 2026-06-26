<?php $__env->startSection('title', 'Dashboard Petugas | SanitaCheck'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center bg-white p-3 rounded-3 shadow-sm mb-3">
    <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-2 me-3">
        <i class="bi bi-shield-check text-success fs-3"></i>
    </div>
    <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Selamat Datang, <?php echo e($petugas->nama_petugas ?? Auth::user()->name); ?>!</h6>
        <p class="text-secondary mb-0 small" style="font-size: 0.75rem;">Panel monitoring inspeksi sanitasi kamu.</p>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-body p-2 d-flex align-items-center">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-success bg-opacity-10 me-2" style="width: 35px; height: 35px; flex-shrink: 0;">
                    <i class="bi bi-calendar2-check text-success fs-6"></i>
                </div>
                <div class="text-truncate">
                    <div class="fw-bold text-dark lh-1" style="font-size: 1.1rem;"><?php echo e($jadwalHariIni); ?></div>
                    <span class="text-secondary" style="font-size: 0.7rem;">Jadwal Aktif</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-body p-2 d-flex align-items-center">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-primary bg-opacity-10 me-2" style="width: 35px; height: 35px; flex-shrink: 0;">
                    <i class="bi bi-clipboard-check text-primary fs-6"></i>
                </div>
                <div class="text-truncate">
                    <div class="fw-bold text-dark lh-1" style="font-size: 1.1rem;"><?php echo e($inspeksiSelesai); ?></div>
                    <span class="text-secondary" style="font-size: 0.7rem;">Total Inspeksi</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-body p-2 d-flex align-items-center">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-warning bg-opacity-10 me-2" style="width: 35px; height: 35px; flex-shrink: 0;">
                    <i class="bi bi-hourglass-split text-warning fs-6"></i>
                </div>
                <div class="text-truncate">
                    <div class="fw-bold text-dark lh-1" style="font-size: 1.1rem;"><?php echo e($jadwalMenunggu); ?></div>
                    <span class="text-secondary" style="font-size: 0.7rem;">Menunggu</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-body p-2 d-flex align-items-center">
                <div class="d-flex justify-content-center align-items-center rounded-circle me-2" style="width: 35px; height: 35px; flex-shrink: 0; background-color: rgba(25, 135, 84, 0.1);">
                    <i class="bi bi-check-circle text-success fs-6"></i>
                </div>
                <div class="text-truncate">
                    <div class="fw-bold text-dark lh-1" style="font-size: 1.1rem;"><?php echo e($jadwalSelesai); ?></div>
                    <span class="text-secondary" style="font-size: 0.7rem;">Selesai</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
    <div class="card-body p-3">
        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;"><i class="bi bi-calendar3 text-success me-2"></i>Jadwal Inspeksi Terbaru</h6>
        
        <?php if($jadwalTerbaru->isEmpty()): ?>
        <div class="text-center py-3">
            <p class="text-secondary mb-0 small" style="font-size: 0.75rem;">Belum ada jadwal inspeksi.</p>
        </div>
        <?php else: ?>
        <div class="d-flex flex-column gap-2">
            <?php $__currentLoopData = $jadwalTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border rounded p-2" style="border-radius: 8px; background-color: #fafafa;">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                    <div class="text-truncate">
                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;"><?php echo e($jadwal->jenis_tugas); ?></div>
                        <div class="text-secondary text-truncate" style="font-size: 0.75rem;">
                            <i class="bi bi-building me-1"></i><?php echo e($jadwal->fasilitas->nama_fasilitas ?? '-'); ?> 
                            <span class="text-muted">(<?php echo e($jadwal->fasilitas->lokasi ?? '-'); ?>)</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <?php if($jadwal->status == 'Menunggu'): ?>
                        <span class="badge bg-secondary" style="font-size: 0.6rem; padding: 0.2em 0.4em;">Menunggu</span>
                        <?php elseif($jadwal->status == 'Berlangsung'): ?>
                        <span class="badge bg-primary" style="font-size: 0.6rem; padding: 0.2em 0.4em;">Berlangsung</span>
                        <?php elseif($jadwal->status == 'Selesai'): ?>
                        <span class="badge bg-success" style="font-size: 0.6rem; padding: 0.2em 0.4em;">Selesai</span>
                        <?php elseif($jadwal->status == 'Dibatalkan'): ?>
                        <span class="badge bg-danger" style="font-size: 0.6rem; padding: 0.2em 0.4em;">Dibatalkan</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top pt-1 mt-1" style="font-size: 0.7rem;">
                    <span class="text-secondary"><i class="bi bi-calendar me-1"></i><?php echo e($jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-'); ?> - <?php echo e($jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-'); ?></span>
                    <a href="<?php echo e(route('petugas.jadwal')); ?>" class="text-success fw-semibold text-decoration-none">Detail <i class="bi bi-chevron-right small"></i></a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-body p-2 text-center" style="background-color: #fcfcfc; border-radius: 10px;">
        <p class="text-secondary mb-0" style="font-size: 0.75rem;">
            <i class="bi bi-info-circle me-1 text-success"></i> Pilih menu <strong>Jadwal</strong> untuk melihat semua data.
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/petugas/dashboard.blade.php ENDPATH**/ ?>