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
                            <?php if($tugas->status == 'selesai'): ?>
                                <?php if($tugas->approval_status == 'pending'): ?>
                                    <span class="badge bg-warning text-dark flex-shrink-0" style="font-size: 0.65rem;">Menunggu ACC</span>
                                <?php elseif($tugas->approval_status == 'rejected'): ?>
                                    <span class="badge bg-danger flex-shrink-0" style="font-size: 0.65rem;">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary flex-shrink-0" style="font-size: 0.65rem;">Selesai</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge bg-success flex-shrink-0" style="font-size: 0.65rem;">Bergabung</span>
                            <?php endif; ?>
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

<?php if(isset($riwayatTugas) && $riwayatTugas->isNotEmpty()): ?>
<div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-1">
        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;"><i class="bi bi-clock-history text-success me-2"></i>Riwayat Tugas Saya</h6>
    </div>
    <div class="card-body p-3">
        <div class="row g-3">
            <?php $__currentLoopData = $riwayatTugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between bg-light">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                            <div class="fw-bold text-dark text-truncate-2" style="font-size: 0.85rem; line-height: 1.3;"><?php echo e($tugas->judul); ?></div>
                            <span class="badge bg-success flex-shrink-0" style="font-size: 0.65rem;">Selesai (ACC)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 text-secondary" style="font-size: 0.75rem;">
                            <div class="text-truncate me-2">
                                <i class="bi bi-building me-1"></i><?php echo e($tugas->fasilitas->nama_fasilitas ?? '-'); ?>

                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-check me-1"></i><?php echo e($tugas->tanggal_tugas->format('d M Y')); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 btn-detail-tugas" data-tugas-id="riwayat-<?php echo e($tugas->id); ?>" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

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
                        <?php
                            $sortedUsers = $tugas->users->sortByDesc(function($user) use ($tugas) {
                                return $user->id === $tugas->ketua_id ? 1 : 0;
                            });
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $sortedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="text-secondary mb-1" style="font-size: 0.8rem;">
                                <i class="bi bi-person-fill me-1"></i><span class="fw-medium"><?php echo e($user->petugas->nama_petugas ?? $user->name); ?></span>
                                <?php if($tugas->ketua_id == $user->id): ?>
                                    <span class="badge bg-success ms-1" style="font-size: 0.65rem;"><i class="bi bi-star-fill me-1"></i>Ketua</span>
                                <?php elseif(!$tugas->ketua_id && $user->pivot->voted_ketua_id): ?>
                                    <span class="badge bg-info text-white ms-1" style="font-size: 0.6rem;"><i class="bi bi-check-circle me-1"></i>Sudah Vote</span>
                                <?php elseif(!$tugas->ketua_id && !$user->pivot->voted_ketua_id): ?>
                                    <span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">Belum Vote</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="text-muted fst-italic">-</li>
                        <?php endif; ?>
                    </ul>
                    <?php
                        $votingStatus = $tugas->voting_status;
                    ?>
                    <?php if($votingStatus['status'] != 'not_applicable'): ?>
                    <div class="mt-2">
                        <div class="alert <?php if($votingStatus['status'] == 'unanimous'): ?> alert-success <?php elseif($votingStatus['status'] == 'conflict'): ?> alert-danger <?php else: ?> alert-info <?php endif; ?> py-2 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                            <i class="bi <?php if($votingStatus['status'] == 'unanimous'): ?> bi-check-circle-fill <?php elseif($votingStatus['status'] == 'conflict'): ?> bi-exclamation-triangle-fill <?php else: ?> bi-info-circle-fill <?php endif; ?> me-1"></i><?php echo e($votingStatus['message']); ?>

                        </div>
                        <?php if($votingStatus['status'] == 'conflict' && $tugas->isUserJoined(auth()->id())): ?>
                        <form action="<?php echo e(route('petugas.tugas-inspeksi.reset-voting', $tugas->id)); ?>" method="POST" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-warning w-100" style="font-size: 0.7rem; border-radius: 6px;">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset Voting
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php if($tugas->isUserJoined(auth()->id()) && $tugas->petugas_terdaftar > 1 && $votingStatus['status'] != 'unanimous' && !$tugas->hasUserVoted(auth()->id())): ?>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalPilihKetua<?php echo e($tugas->id); ?>" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-person-plus me-1"></i>Pilih Ketua
                        </button>
                    </div>
                    <?php endif; ?>
                    <?php if($tugas->ketua_id == auth()->id()): ?>
                    <div class="mt-3 pt-2 border-top">
                        <?php if($tugas->status == 'selesai' && $tugas->approval_status == 'pending'): ?>
                        <div class="alert alert-warning py-2 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-hourglass-split me-1"></i><strong>Menunggu ACC Admin</strong><br>
                            Laporan sedang ditinjau.
                        </div>
                        <?php else: ?>
                            <?php if($tugas->approval_status == 'rejected'): ?>
                            <div class="alert alert-danger py-2 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                                <i class="bi bi-x-circle-fill me-1"></i><strong>Laporan Ditolak!</strong><br>
                                Silakan perbaiki dan kirim ulang laporan.
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info py-2 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                                <i class="bi bi-star-fill me-1"></i><strong>Anda adalah Ketua Tim</strong><br>
                                Anda bertanggung jawab untuk mengirim laporan inspeksi.
                            </div>
                            <?php endif; ?>
                            <button type="button" class="btn btn-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalLaporan<?php echo e($tugas->id); ?>" style="background-color: #1b5e3a; border-color: #1b5e3a; border-radius: 6px;">
                                <i class="bi bi-file-earmark-plus me-1"></i><?php echo e($tugas->approval_status == 'rejected' ? 'Edit & Kirim Ulang' : 'Kirim Laporan'); ?>

                            </button>
                        <?php endif; ?>
                    </div>
                    <?php elseif($tugas->ketua_id && $tugas->ketua_id != auth()->id()): ?>
                    <div class="mt-3 pt-2 border-top">
                        <?php if($tugas->status == 'selesai' && $tugas->approval_status == 'pending'): ?>
                        <div class="alert alert-warning py-2 px-2 text-center" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-hourglass-split me-1"></i>Laporan tim sedang menunggu ACC Admin.
                        </div>
                        <?php elseif($tugas->approval_status == 'rejected'): ?>
                        <div class="alert alert-danger py-2 px-2 text-center" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-x-circle-fill me-1"></i>Laporan ditolak Admin. Ketua sedang merevisi.
                        </div>
                        <?php else: ?>
                        <div class="alert alert-secondary py-2 px-2 text-center" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-person-check me-1"></i><strong>Anda adalah Anggota Tim</strong><br>
                            Ketua tim sedang bertanggung jawab mengisi form inspeksi.
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
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
                        <?php
                            $sortedUsers = $tugas->users->sortByDesc(function($user) use ($tugas) {
                                return $user->id === $tugas->ketua_id ? 1 : 0;
                            });
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $sortedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="text-secondary mb-1" style="font-size: 0.8rem;">
                                <i class="bi bi-person-fill me-1"></i><span class="fw-medium"><?php echo e($user->petugas->nama_petugas ?? $user->name); ?></span>
                                <?php if($tugas->ketua_id == $user->id): ?>
                                    <span class="badge bg-success ms-1" style="font-size: 0.65rem;"><i class="bi bi-star-fill me-1"></i>Ketua</span>
                                <?php elseif(!$tugas->ketua_id && $user->pivot->voted_ketua_id): ?>
                                    <span class="badge bg-info text-white ms-1" style="font-size: 0.6rem;"><i class="bi bi-check-circle me-1"></i>Sudah Vote</span>
                                <?php elseif(!$tugas->ketua_id && !$user->pivot->voted_ketua_id): ?>
                                    <span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">Belum Vote</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="text-muted fst-italic">-</li>
                        <?php endif; ?>
                    </ul>
                    <?php
                        $votingStatus = $tugas->voting_status;
                    ?>
                    <?php if($votingStatus['status'] != 'not_applicable'): ?>
                    <div class="mt-2">
                        <div class="alert <?php if($votingStatus['status'] == 'unanimous'): ?> alert-success <?php elseif($votingStatus['status'] == 'conflict'): ?> alert-danger <?php else: ?> alert-info <?php endif; ?> py-2 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                            <i class="bi <?php if($votingStatus['status'] == 'unanimous'): ?> bi-check-circle-fill <?php elseif($votingStatus['status'] == 'conflict'): ?> bi-exclamation-triangle-fill <?php else: ?> bi-info-circle-fill <?php endif; ?> me-1"></i><?php echo e($votingStatus['message']); ?>

                        </div>
                        <?php if($votingStatus['status'] == 'conflict' && $isJoined): ?>
                        <form action="<?php echo e(route('petugas.tugas-inspeksi.reset-voting', $tugas->id)); ?>" method="POST" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-warning w-100" style="font-size: 0.7rem; border-radius: 6px;">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset Voting
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php if($isJoined && $tugas->petugas_terdaftar > 1 && $votingStatus['status'] != 'unanimous' && !$tugas->hasUserVoted(auth()->id())): ?>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalPilihKetuaSemua<?php echo e($tugas->id); ?>" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-person-plus me-1"></i>Pilih Ketua
                        </button>
                    </div>
                    <?php endif; ?>
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

<!-- Modal Submit Report for Ketua -->
<?php $__currentLoopData = $tugasSaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if($tugas->ketua_id == auth()->id()): ?>
<div class="modal fade" id="modalLaporan<?php echo e($tugas->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-success"><i class="bi bi-file-earmark-plus me-2"></i>Kirim Laporan Inspeksi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <form action="<?php echo e(route('tugas-inspeksi.submit-report', $tugas->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-3" style="font-size: 0.8rem;">
                    <div class="mb-2"><strong>Tugas:</strong> <?php echo e($tugas->judul); ?></div>
                    <div class="mb-2"><strong>Fasilitas:</strong> <?php echo e($tugas->fasilitas->nama_fasilitas ?? '-'); ?></div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kondisi Kebersihan</label>
                        <select name="kondisi_kebersihan" class="form-select form-select-sm" required>
                            <option value="">Pilih kondisi</option>
                            <option value="Baik" <?php echo e($tugas->kondisi_kebersihan == 'Baik' ? 'selected' : ''); ?>>Baik</option>
                            <option value="Cukup" <?php echo e($tugas->kondisi_kebersihan == 'Cukup' ? 'selected' : ''); ?>>Cukup</option>
                            <option value="Buruk" <?php echo e($tugas->kondisi_kebersihan == 'Buruk' ? 'selected' : ''); ?>>Buruk</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Ketersediaan Air</label>
                            <select name="ketersediaan_air" class="form-select form-select-sm" required>
                                <option value="">Pilih</option>
                                <option value="1" <?php echo e($tugas->ketersediaan_air === '1' || $tugas->ketersediaan_air === 1 ? 'selected' : ''); ?>>Ada</option>
                                <option value="0" <?php echo e($tugas->ketersediaan_air === '0' || $tugas->ketersediaan_air === 0 ? 'selected' : ''); ?>>Tidak Ada</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Ketersediaan Sabun</label>
                            <select name="ketersediaan_sabun" class="form-select form-select-sm" required>
                                <option value="">Pilih</option>
                                <option value="1" <?php echo e($tugas->ketersediaan_sabun === '1' || $tugas->ketersediaan_sabun === 1 ? 'selected' : ''); ?>>Ada</option>
                                <option value="0" <?php echo e($tugas->ketersediaan_sabun === '0' || $tugas->ketersediaan_sabun === 0 ? 'selected' : ''); ?>>Tidak Ada</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Bau Tidak Sedap?</label>
                        <select name="bau_tidak_sedap" class="form-select form-select-sm" required>
                            <option value="">Pilih</option>
                            <option value="1" <?php echo e($tugas->bau_tidak_sedap === '1' || $tugas->bau_tidak_sedap === 1 ? 'selected' : ''); ?>>Ya</option>
                            <option value="0" <?php echo e($tugas->bau_tidak_sedap === '0' || $tugas->bau_tidak_sedap === 0 ? 'selected' : ''); ?>>Tidak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan Laporan</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="3" required placeholder="Jelaskan hasil inspeksi (min. 10 karakter)"><?php echo e($tugas->laporan_catatan); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Foto Bukti (Opsional)</label>
                        <?php if($tugas->laporan_foto): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(asset($tugas->laporan_foto)); ?>" alt="Foto Bukti Sebelumnya" class="img-thumbnail" style="max-height: 100px;">
                                <div class="small text-muted mt-1">Pilih foto baru untuk mengganti foto sebelumnya.</div>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="foto" class="form-control form-control-sm" accept="image/jpeg,image/png">
                        <small class="text-secondary">Format: JPG, PNG (Max 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer border-top pt-0 px-3 pb-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" style="background-color: #1b5e3a; border-color: #1b5e3a; border-radius: 6px;">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Modal Pilih Ketua for Tugas Saya -->
<?php $__currentLoopData = $tugasSaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if($tugas->isUserJoined(auth()->id()) && $tugas->petugas_terdaftar > 1): ?>
<div class="modal fade" id="modalPilihKetua<?php echo e($tugas->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-primary"><i class="bi bi-person-plus me-2"></i>Pilih Ketua Tim</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Tugas:</strong> <?php echo e($tugas->judul); ?></div>
                <div class="mb-3 text-muted small">Pilih salah satu anggota tim untuk menjadi ketua:</div>
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $tugas->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="showConfirmKetua(<?php echo e($tugas->id); ?>, <?php echo e($user->id); ?>, '<?php echo e($user->petugas->nama_petugas ?? $user->name); ?>')" style="font-size: 0.8rem;">
                            <span>
                                <i class="bi bi-person-fill me-2"></i><?php echo e($user->petugas->nama_petugas ?? $user->name); ?>

                            </span>
                            <?php if($tugas->ketua_id == $user->id): ?>
                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Ketua</span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-muted fst-italic text-center py-2">-</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pilih Ketua for Tugas Saya -->
<div class="modal fade" id="modalConfirmKetua<?php echo e($tugas->id); ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-person-check text-primary" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Pilih Ketua Tim?</h6>
                <p class="text-secondary small mb-4">Apakah Anda yakin ingin memilih <strong id="ketuaName<?php echo e($tugas->id); ?>"></strong> sebagai ketua tim?</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">Batal</button>
                    <button type="button" class="btn btn-primary flex-grow-1" id="btnConfirmKetua<?php echo e($tugas->id); ?>" style="border-radius: 8px; font-size: 0.85rem;">Ya, Pilih</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Modal Pilih Ketua for Semua Tugas -->
<?php $__currentLoopData = $tugasTersedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $isJoined = $tugas->isUserJoined(auth()->id());
?>
<?php if($isJoined && $tugas->petugas_terdaftar > 1): ?>
<div class="modal fade" id="modalPilihKetuaSemua<?php echo e($tugas->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-primary"><i class="bi bi-person-plus me-2"></i>Pilih Ketua Tim</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Tugas:</strong> <?php echo e($tugas->judul); ?></div>
                <div class="mb-3 text-muted small">Pilih salah satu anggota tim untuk menjadi ketua:</div>
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $tugas->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="showConfirmKetua(<?php echo e($tugas->id); ?>, <?php echo e($user->id); ?>, '<?php echo e($user->petugas->nama_petugas ?? $user->name); ?>')" style="font-size: 0.8rem;">
                            <span>
                                <i class="bi bi-person-fill me-2"></i><?php echo e($user->petugas->nama_petugas ?? $user->name); ?>

                            </span>
                            <?php if($tugas->ketua_id == $user->id): ?>
                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Ketua</span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-muted fst-italic text-center py-2">-</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pilih Ketua for Semua Tugas -->
<div class="modal fade" id="modalConfirmKetuaSemua<?php echo e($tugas->id); ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-person-check text-primary" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Pilih Ketua Tim?</h6>
                <p class="text-secondary small mb-4">Apakah Anda yakin ingin memilih <strong id="ketuaNameSemua<?php echo e($tugas->id); ?>"></strong> sebagai ketua tim?</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">Batal</button>
                    <button type="button" class="btn btn-primary flex-grow-1" id="btnConfirmKetuaSemua<?php echo e($tugas->id); ?>" style="border-radius: 8px; font-size: 0.85rem;">Ya, Pilih</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Modal Detail Riwayat Tugas -->
<?php if(isset($riwayatTugas)): ?>
<?php $__currentLoopData = $riwayatTugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalRiwayatTugas<?php echo e($tugas->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-success"><i class="bi bi-clock-history me-2"></i>Detail Riwayat</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Judul:</strong> <br><?php echo e($tugas->judul); ?></div>
                <div class="mb-2"><strong>Fasilitas:</strong> <br><?php echo e($tugas->fasilitas->nama_fasilitas ?? '-'); ?></div>
                <div class="mb-2"><strong>Waktu:</strong> <br><?php echo e($tugas->tanggal_tugas->format('d M Y, H:i')); ?></div>
                
                <div class="mt-3 pt-2 border-top">
                    <h6 class="fw-bold mb-2 small text-success">Hasil Laporan</h6>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Kondisi</div>
                            <div class="fw-medium"><?php echo e($tugas->kondisi_kebersihan); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Bau</div>
                            <div class="fw-medium"><?php echo e($tugas->bau_tidak_sedap ? 'Ya' : 'Tidak'); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Air</div>
                            <div class="fw-medium"><?php echo e($tugas->ketersediaan_air ? 'Ada' : 'Tidak Ada'); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Sabun</div>
                            <div class="fw-medium"><?php echo e($tugas->ketersediaan_sabun ? 'Ada' : 'Tidak Ada'); ?></div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted" style="font-size: 0.7rem;">Catatan:</div>
                        <div class="bg-light p-2 rounded" style="font-size: 0.75rem;"><?php echo e($tugas->laporan_catatan); ?></div>
                    </div>
                </div>
                
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Tim Petugas:</strong>
                        <span class="badge bg-info bg-opacity-10 text-info"><?php echo e($tugas->petugas_terdaftar); ?>/<?php echo e($tugas->kuota_tim); ?></span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <?php
                            $sortedUsers = $tugas->users->sortByDesc(function($user) use ($tugas) {
                                return $user->id === $tugas->ketua_id ? 1 : 0;
                            });
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $sortedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="text-secondary mb-1" style="font-size: 0.8rem;">
                                <i class="bi bi-person-fill me-1"></i><span class="fw-medium"><?php echo e($user->petugas->nama_petugas ?? $user->name); ?></span>
                                <?php if($tugas->ketua_id == $user->id): ?>
                                    <span class="badge bg-success ms-1" style="font-size: 0.65rem;"><i class="bi bi-star-fill me-1"></i>Ketua</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="text-muted fst-italic">-</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-3 pb-3">
                <button type="button" class="btn btn-sm btn-secondary w-100" data-bs-dismiss="modal" style="border-radius: 6px;">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-detail-tugas').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var tugasId = this.getAttribute('data-tugas-id');
            var prefix = tugasId.startsWith('saya-') ? 'modalTugasSaya' : (tugasId.startsWith('riwayat-') ? 'modalRiwayatTugas' : 'modalSemuaTugas');
            var id = tugasId.replace('saya-', '').replace('semua-', '').replace('riwayat-', '');
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

    var selectedKetua = { tugasId: null, userId: null, userName: null };

    window.showConfirmKetua = function(tugasId, userId, userName) {
        selectedKetua = { tugasId: tugasId, userId: userId, userName: userName };
        
        // Close the pilih ketua modal
        var pilihKetuaModal = document.getElementById('modalPilihKetua' + tugasId) || document.getElementById('modalPilihKetuaSemua' + tugasId);
        if (pilihKetuaModal) {
            var bsModal = bootstrap.Modal.getInstance(pilihKetuaModal);
            if (bsModal) bsModal.hide();
        }

        // Show confirm modal
        var confirmModalId = document.getElementById('modalPilihKetua' + tugasId) ? 'modalConfirmKetua' + tugasId : 'modalConfirmKetuaSemua' + tugasId;
        var ketuaNameId = document.getElementById('modalPilihKetua' + tugasId) ? 'ketuaName' + tugasId : 'ketuaNameSemua' + tugasId;
        
        document.getElementById(ketuaNameId).textContent = userName;
        
        var confirmModal = document.getElementById(confirmModalId);
        if (confirmModal) {
            var bsConfirmModal = new bootstrap.Modal(confirmModal);
            bsConfirmModal.show();
        }
    };

    function confirmSelectKetua() {
        if (!selectedKetua.tugasId) return;

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/area-petugas/tugas-inspeksi/' + selectedKetua.tugasId + '/set-ketua';
        
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            var csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }
        
        var methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
        
        var ketuaInput = document.createElement('input');
        ketuaInput.type = 'hidden';
        ketuaInput.name = 'ketua_id';
        ketuaInput.value = selectedKetua.userId;
        form.appendChild(ketuaInput);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Attach event listeners to confirm buttons
    document.querySelectorAll('[id^="btnConfirmKetua"]').forEach(function(btn) {
        btn.addEventListener('click', confirmSelectKetua);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/petugas/tugas-inspeksi.blade.php ENDPATH**/ ?>