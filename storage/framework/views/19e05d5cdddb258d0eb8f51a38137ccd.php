<?php $__env->startSection('title', 'Riwayat Inspeksi | SanitaCheck'); ?>

<?php $__env->startSection('content'); ?>
<h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-success me-2"></i>Riwayat Inspeksi</h5>

<!-- Statistik Ringkasan -->
<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body text-center p-2">
                <div class="fw-bold fs-4 text-success"><?php echo e($totalInspeksi); ?></div>
                <div class="text-secondary" style="font-size: 0.7rem;">Total Inspeksi</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body text-center p-2">
                <div class="fw-bold fs-4 text-primary"><?php echo e($inspeksiBulanIni); ?></div>
                <div class="text-secondary" style="font-size: 0.7rem;">Bulan Ini</div>
            </div>
        </div>
    </div>
</div>

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
                <?php
                    $kondisi = strtolower($inspeksi->kondisi_kebersihan ?? '');
                ?>
                <span class="badge 
                    <?php echo e($kondisi === 'baik' ? 'bg-success' : ''); ?>

                    <?php echo e($kondisi === 'cukup' ? 'bg-warning text-dark' : ''); ?>

                    <?php echo e($kondisi === 'buruk' ? 'bg-danger' : ''); ?>

                " style="font-size: 0.7rem;"><?php echo e(ucfirst($inspeksi->kondisi_kebersihan)); ?></span>
            </div>
            
            <!-- Detail Inspeksi -->
            <div class="row mt-2 mb-2">
                <div class="col-4 text-center">
                    <div class="small text-secondary">Air</div>
                    <?php $air = strtolower($inspeksi->ketersediaan_air ?? ''); ?>
                    <i class="bi <?php echo e(($air == 'ada' || $air == '1') ? 'bi-droplet-fill text-primary' : 'bi-droplet text-secondary'); ?>"></i>
                    <div style="font-size: 0.65rem;"><?php echo e(($air == 'ada' || $air == '1') ? 'Ada' : 'Tidak'); ?></div>
                </div>
                <div class="col-4 text-center">
                    <div class="small text-secondary">Sabun</div>
                    <?php $sabun = strtolower($inspeksi->ketersediaan_sabun ?? ''); ?>
                    <i class="bi <?php echo e(($sabun == 'ada' || $sabun == '1') ? 'bi-hand-thumbs-up-fill text-success' : 'bi-hand-thumbs-down text-secondary'); ?>"></i>
                    <div style="font-size: 0.65rem;"><?php echo e(($sabun == 'ada' || $sabun == '1') ? 'Ada' : 'Tidak'); ?></div>
                </div>
                <div class="col-4 text-center">
                    <div class="small text-secondary">Bau</div>
                    <?php $bau = strtolower($inspeksi->bau_tidak_sedap ?? ''); ?>
                    <i class="bi <?php echo e(($bau == 'ya' || $bau == '1') ? 'bi-exclamation-triangle-fill text-danger' : 'bi-check-circle-fill text-success'); ?>"></i>
                    <div style="font-size: 0.65rem;"><?php echo e(($bau == 'ya' || $bau == '1') ? 'Berbau' : 'Aman'); ?></div>
                </div>
            </div>

            <?php if($inspeksi->catatan): ?>
            <div class="bg-light rounded p-2 mb-2">
                <div class="small text-secondary"><i class="bi bi-chat-left-text me-1"></i><?php echo e($inspeksi->catatan); ?></div>
            </div>
            <?php endif; ?>
            
            <hr class="my-2">
            <div class="d-flex justify-content-between small text-secondary">
                <span><i class="bi bi-calendar3 me-1"></i><?php echo e($inspeksi->created_at->format('d M Y, H:i')); ?></span>
                <?php $statusTL = strtolower($inspeksi->status_tindak_lanjut ?? ''); ?>
                <span class="fw-semibold 
                    <?php echo e($statusTL == 'selesai' ? 'text-success' : ''); ?>

                    <?php echo e($statusTL == 'menunggu' ? 'text-warning' : ''); ?>

                    <?php echo e(($statusTL == 'aman') ? 'text-success' : ''); ?>

                    <?php echo e(str_contains($statusTL, 'perlu') ? 'text-danger' : ''); ?>

                    <?php echo e($statusTL == 'dibatalkan' ? 'text-danger' : ''); ?>

                "><?php echo e(ucfirst($inspeksi->status_tindak_lanjut)); ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-3">
    <?php echo e($riwayatInspeksi->links('pagination::bootstrap-5')); ?>

</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/petugas/inspeksi/riwayat.blade.php ENDPATH**/ ?>