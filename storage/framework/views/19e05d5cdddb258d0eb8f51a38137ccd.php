<?php $__env->startSection('title', 'Riwayat Inspeksi | SanitaCheck'); ?>

<?php $__env->startSection('content'); ?>
<h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-success me-2"></i>Riwayat Inspeksi</h5>

<?php if($riwayatInspeksi->isEmpty()): ?>
<div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
    <div class="card-body">
        <i class="bi bi-inbox text-secondary" style="font-size: 3rem;"></i>
        <p class="text-secondary mt-2 mb-0">Belum ada riwayat inspeksi.</p>
    </div>
</div>
<?php else: ?>
<div class="d-flex flex-column gap-2">
    <?php $__currentLoopData = $riwayatInspeksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inspeksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-bold text-dark"><?php echo e($inspeksi->fasilitas->nama_fasilitas ?? '-'); ?></div>
                    <div class="text-secondary small"><i class="bi bi-geo-alt me-1"></i><?php echo e($inspeksi->fasilitas->lokasi ?? '-'); ?></div>
                </div>
                <span class="badge 
                    <?php echo e($inspeksi->kondisi_kebersihan === 'baik' ? 'bg-success' : ''); ?>

                    <?php echo e($inspeksi->kondisi_kebersihan === 'cukup' ? 'bg-warning text-dark' : ''); ?>

                    <?php echo e($inspeksi->kondisi_kebersihan === 'buruk' ? 'bg-danger' : ''); ?>

                "><?php echo e(ucfirst($inspeksi->kondisi_kebersihan)); ?></span>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between small text-secondary">
                <span><i class="bi bi-calendar3 me-1"></i><?php echo e($inspeksi->created_at->format('d M Y, H:i')); ?></span>
                <span class="fw-semibold text-capitalize"><?php echo e($inspeksi->status_tindak_lanjut); ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/petugas/inspeksi/riwayat.blade.php ENDPATH**/ ?>