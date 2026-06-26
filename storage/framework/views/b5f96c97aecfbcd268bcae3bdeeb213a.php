<?php $__env->startSection('title', 'Tugas Inspeksi | SanitaCheck'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4 d-flex justify-content-between align-items-center border-bottom pb-2">
    <div>
        <h5 class="fw-bold mb-0" style="color: #1b5e3a;"><i class="bi bi-list-task me-2"></i>Tugas Inspeksi</h5>
        <p class="text-body-secondary mb-0" style="font-size: 0.8rem;">Ambil tugas inspeksi yang tersedia</p>
    </div>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm py-2 px-3 mb-3" role="alert" style="border-radius: 8px; font-size: 0.85rem;">
    <?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show shadow-sm py-2 px-3 mb-3" role="alert" style="border-radius: 8px; font-size: 0.85rem;">
    <?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
</div>
<?php endif; ?>

<?php if($tugasSaya->isNotEmpty()): ?>
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-1">
        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;"><i class="bi bi-person-check text-success me-2"></i>Tugas Saya</h6>
    </div>
    <div class="card-body p-3">
        <div class="row g-3">
            <?php $__currentLoopData = $tugasSaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between" style="background-color: rgba(25, 135, 84, 0.03); border-color: rgba(25, 135, 84, 0.2) !important;">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                            <div class="fw-bold text-dark text-truncate-2" style="font-size: 0.85rem; line-height: 1.3;"><?php echo e($tugas->judul); ?></div>
                            <span class="badge bg-success flex-shrink-0" style="font-size: 0.65rem;">Bergabung</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 text-secondary" style="font-size: 0.75rem;">
                            <div class="text-truncate me-2">
                                <i class="bi bi-building me-1"></i><?php echo e($tugas->fasilitas->nama_fasilitas ?? '-'); ?>

                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-event me-1"></i><?php echo e($tugas->tanggal_tugas->format('d M')); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-auto">
                        <button type="button" class="btn btn-sm btn-outline-info flex-grow-1 btn-detail-tugas" data-tugas-id="saya-<?php echo e($tugas->id); ?>" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                            <i class="bi bi-info-circle me-1"></i>Detail
                        </button>
                        <form action="<?php echo e(route('petugas.tugas-inspeksi.leave', $tugas->id)); ?>" method="POST" class="flex-grow-1">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 btn-keluar-tugas" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                                <i class="bi bi-box-arrow-right me-1"></i>Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-1">
        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;"><i class="bi bi-list-ul text-success me-2"></i>Semua Tugas Aktif</h6>
    </div>
    <div class="card-body p-3">
        <?php if($tugasTersedia->isEmpty()): ?>
        <div class="text-center py-4">
            <p class="text-secondary mb-0 small">Belum ada tugas inspeksi yang tersedia.</p>
        </div>
        <?php else: ?>
        <div class="row g-3">
            <?php $__currentLoopData = $tugasTersedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isJoined = $tugas->isUserJoined(auth()->id());
                $isFull = $tugas->isFull();
            ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                            <div class="fw-bold text-dark text-truncate-2" style="font-size: 0.85rem; line-height: 1.3;"><?php echo e($tugas->judul); ?></div>
                            <div class="flex-shrink-0">
                                <?php if($isFull): ?>
                                    <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Penuh</span>
                                <?php elseif($isJoined): ?>
                                    <span class="badge bg-success" style="font-size: 0.65rem;">Joined</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary" style="font-size: 0.65rem;">Tersedia</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 text-secondary" style="font-size: 0.75rem;">
                            <div class="text-truncate me-2">
                                <i class="bi bi-building me-1"></i><?php echo e($tugas->fasilitas->nama_fasilitas ?? '-'); ?>

                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-event me-1"></i><?php echo e($tugas->tanggal_tugas->format('d M')); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-auto">
                        <button type="button" class="btn btn-sm btn-outline-info flex-grow-1 btn-detail-tugas" data-tugas-id="semua-<?php echo e($tugas->id); ?>" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                            <i class="bi bi-info-circle me-1"></i>Detail
                        </button>
                        
                        <?php if($isJoined): ?>
                            <form action="<?php echo e(route('petugas.tugas-inspeksi.leave', $tugas->id)); ?>" method="POST" class="flex-grow-1">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 btn-keluar-tugas" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                                </button>
                            </form>
                        <?php elseif($isFull): ?>
                            <button type="button" class="btn btn-sm btn-secondary flex-grow-1" disabled style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                                <i class="bi bi-x-circle me-1"></i>Penuh
                            </button>
                        <?php else: ?>
                            <form action="<?php echo e(route('petugas.tugas-inspeksi.join', $tugas->id)); ?>" method="POST" class="flex-grow-1">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-success text-white w-100" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem; background-color: #1b5e3a; border-color: #1b5e3a;">
                                    <i class="bi bi-check-circle me-1"></i>Ambil
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
<?php $__currentLoopData = $tugasSaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalTugasSaya<?php echo e($tugas->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-success"><i class="bi bi-info-circle me-2"></i>Detail Tugas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Judul:</strong> <br><?php echo e($tugas->judul); ?></div>
                <div class="mb-2"><strong>Fasilitas:</strong> <br><?php echo e($tugas->fasilitas->nama_fasilitas ?? '-'); ?> (<?php echo e($tugas->fasilitas->lokasi ?? '-'); ?>)</div>
                <div class="mb-2"><strong>Waktu:</strong> <br><?php echo e($tugas->tanggal_tugas->format('d M Y, H:i')); ?></div>
                <div class="mb-2"><strong>Deskripsi:</strong> <br><span class="text-secondary"><?php echo e($tugas->deskripsi ?: '-'); ?></span></div>
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Tim Petugas:</strong>
                        <span class="badge bg-info bg-opacity-10 text-info"><?php echo e($tugas->petugas_terdaftar); ?>/<?php echo e($tugas->kuota_tim); ?></span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <?php $__empty_1 = true; $__currentLoopData = $tugas->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="text-secondary mb-1"><i class="bi bi-person-fill me-1"></i><?php echo e($user->petugas->nama_petugas ?? $user->name); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="text-muted fst-italic">-</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__currentLoopData = $tugasTersedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $isJoined = $tugas->isUserJoined(auth()->id());
    $isFull = $tugas->isFull();
?>
<div class="modal fade" id="modalSemuaTugas<?php echo e($tugas->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-success"><i class="bi bi-info-circle me-2"></i>Detail Tugas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Judul:</strong> <br><?php echo e($tugas->judul); ?></div>
                <div class="mb-2"><strong>Fasilitas:</strong> <br><?php echo e($tugas->fasilitas->nama_fasilitas ?? '-'); ?> (<?php echo e($tugas->fasilitas->lokasi ?? '-'); ?>)</div>
                <div class="mb-2"><strong>Waktu:</strong> <br><?php echo e($tugas->tanggal_tugas->format('d M Y, H:i')); ?></div>
                <div class="mb-2"><strong>Deskripsi:</strong> <br><span class="text-secondary"><?php echo e($tugas->deskripsi ?: '-'); ?></span></div>
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Tim Petugas:</strong>
                        <span class="badge bg-info bg-opacity-10 text-info"><?php echo e($tugas->petugas_terdaftar); ?>/<?php echo e($tugas->kuota_tim); ?></span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <?php $__empty_1 = true; $__currentLoopData = $tugas->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="text-secondary mb-1"><i class="bi bi-person-fill me-1"></i><?php echo e($user->petugas->nama_petugas ?? $user->name); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="text-muted fst-italic">-</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-3 pb-3">
                <?php if($isJoined): ?>
                    <form action="<?php echo e(route('petugas.tugas-inspeksi.leave', $tugas->id)); ?>" method="POST" class="w-100">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 btn-keluar-tugas" style="border-radius: 6px;">Keluar dari Tim</button>
                    </form>
                <?php elseif(!$isFull): ?>
                    <form action="<?php echo e(route('petugas.tugas-inspeksi.join', $tugas->id)); ?>" method="POST" class="w-100">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-success text-white w-100" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a;">Bergabung Sekarang</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div class="modal fade" id="modalConfirmKeluar" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-circle text-warning" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Keluar dari Tim?</h6>
                <p class="text-secondary small mb-4">Apakah Anda yakin ingin keluar dari tugas inspeksi ini? Posisi Anda mungkin akan digantikan oleh petugas lain.</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">Batal</button>
                    <button type="button" class="btn btn-danger flex-grow-1" id="btnConfirmKeluar" style="border-radius: 8px; font-size: 0.85rem;">Ya, Keluar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-detail-tugas').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var tugasId = this.getAttribute('data-tugas-id');
            var prefix = tugasId.startsWith('saya-') ? 'modalTugasSaya' : 'modalSemuaTugas';
            var id = tugasId.replace('saya-', '').replace('semua-', '');
            var modalEl = document.getElementById(prefix + id);
            if (modalEl) {
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
    });

    var formToSubmit = null;
    var modalConfirmEl = document.getElementById('modalConfirmKeluar');
    var confirmModal = null;
    
    if (modalConfirmEl) {
        confirmModal = new bootstrap.Modal(modalConfirmEl);
    }

    document.querySelectorAll('.btn-keluar-tugas').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            formToSubmit = this.closest('form');
            
            var parentModal = this.closest('.modal');
            if(parentModal && parentModal.id !== 'modalConfirmKeluar') {
                var bsModal = bootstrap.Modal.getInstance(parentModal);
                if(bsModal) bsModal.hide();
            }

            if (confirmModal) {
                setTimeout(function() {
                    confirmModal.show();
                }, 300);
            }
        });
    });

    if (document.getElementById('btnConfirmKeluar')) {
        document.getElementById('btnConfirmKeluar').addEventListener('click', function() {
            if(formToSubmit) {
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                this.disabled = true;
                formToSubmit.submit();
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/petugas/tugas-inspeksi.blade.php ENDPATH**/ ?>