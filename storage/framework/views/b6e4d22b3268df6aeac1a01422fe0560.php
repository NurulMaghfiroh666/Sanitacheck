<?php $__env->startSection('title', 'Jadwal Inspeksi | SanitaCheck'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <h5 class="fw-bold mb-1"><i class="bi bi-calendar2-check text-success me-2"></i>Jadwal Inspeksi</h5>
    <p class="text-body-secondary mb-0 d-md-none" style="font-size: 0.8rem;">Daftar jadwal penugasan inspeksi Anda</p>
</div>

<?php if($jadwalDitugaskan->isEmpty()): ?>
<div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
    <div class="card-body">
        <i class="bi bi-calendar-x text-secondary" style="font-size: 3rem;"></i>
        <p class="text-secondary mt-2 mb-0">Belum ada jadwal inspeksi saat ini.</p>
    </div>
</div>
<?php else: ?>

<div class="d-block d-md-none">
    <?php $__currentLoopData = $jadwalDitugaskan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; border-left: 4px solid 
        <?php if($jadwal->status == 'Menunggu'): ?> #6c757d 
        <?php elseif($jadwal->status == 'Berlangsung'): ?> #0d6efd 
        <?php elseif($jadwal->status == 'Selesai'): ?> #198754 
        <?php else: ?> #dc3545 <?php endif; ?> !important;">
        
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                <div>
                    <span class="text-secondary small fw-bold">#<?php echo e($index + 1); ?></span>
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?php echo e($jadwal->jenis_tugas); ?></div>
                </div>
                <div>
                    <?php if($jadwal->status == 'Menunggu'): ?>
                        <span class="badge bg-secondary" style="font-size: 0.65rem;">Menunggu</span>
                    <?php elseif($jadwal->status == 'Berlangsung'): ?>
                        <span class="badge bg-primary" style="font-size: 0.65rem;">Berlangsung</span>
                    <?php elseif($jadwal->status == 'Selesai'): ?>
                        <span class="badge bg-success" style="font-size: 0.65rem;">Selesai</span>
                    <?php elseif($jadwal->status == 'Dibatalkan'): ?>
                        <span class="badge bg-danger" style="font-size: 0.65rem;">Dibatalkan</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3" style="font-size: 0.8rem;">
                <div class="text-dark fw-semibold mb-1">
                    <i class="bi bi-building me-1 text-secondary"></i><?php echo e($jadwal->fasilitas->nama_fasilitas ?? '-'); ?>

                </div>
                <div class="text-secondary mb-2" style="padding-left: 15px;">
                    <?php echo e($jadwal->fasilitas->lokasi ?? '-'); ?>

                </div>
                <div class="text-secondary">
                    <i class="bi bi-calendar-event me-1 text-secondary"></i> 
                    <?php echo e($jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-'); ?> 
                    <span class="text-muted">s/d</span> 
                    <?php echo e($jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-'); ?>

                </div>
            </div>

            <div class="p-2 rounded-3 bg-light mb-3 d-flex justify-content-between align-items-center" style="font-size: 0.75rem;">
                <div><i class="bi bi-images me-1 text-success"></i><strong><?php echo e($jadwal->photos->count()); ?></strong> Foto</div>
                <?php if($jadwal->catatan): ?>
                    <div class="text-secondary text-truncate ms-2" style="max-width: 180px;" title="<?php echo e($jadwal->catatan); ?>">
                        <i class="bi bi-sticky me-1"></i><?php echo e($jadwal->catatan); ?>

                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <a href="<?php echo e(route('petugas.upload-foto', $jadwal->id)); ?>" class="btn btn-success btn-sm fw-semibold flex-grow-1 py-2" style="border-radius: 8px; font-size: 0.8rem;">
                    <i class="bi bi-camera me-1"></i> Upload
                </a>
                <?php if($jadwal->status != 'Selesai'): ?>
                <a href="<?php echo e(route('petugas.inspeksi.create', $jadwal->fasilitas_id)); ?>" class="btn btn-outline-primary btn-sm fw-semibold flex-grow-1 py-2" style="border-radius: 8px; font-size: 0.8rem;">
                    <i class="bi bi-pencil-square me-1"></i> Isi Form
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="card border-0 shadow-sm d-none d-md-block" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0">
                <thead style="border-bottom: 1px solid #dee2e6; background-color: #f8f9fa;">
                    <tr>
                        <th class="py-3 px-3" style="font-size: 0.8rem; font-weight: 600; width: 5%">No</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Jenis Tugas</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600; width: 25%">Fasilitas</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600; width: 20%">Tanggal</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Status</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600; width: 15%">Inspeksi</th>
                        <th class="py-3 text-center px-3" style="font-size: 0.8rem; font-weight: 600; width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $jadwalDitugaskan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td class="py-3 px-3 text-secondary small"><?php echo e($index + 1); ?></td>
                        <td class="py-3">
                            <div class="fw-semibold text-dark" style="font-size: 0.85rem;"><?php echo e($jadwal->jenis_tugas); ?></div>
                        </td>
                        <td class="py-3">
                            <div>
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;"><?php echo e($jadwal->fasilitas->nama_fasilitas ?? '-'); ?></div>
                                <div class="text-secondary small"><?php echo e($jadwal->fasilitas->lokasi ?? '-'); ?></div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="small text-dark">
                                <div><i class="bi bi-calendar2-event me-1 text-muted"></i><?php echo e($jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-'); ?></div>
                                <div class="text-secondary ps-3" style="font-size: 0.75rem;">s/d <?php echo e($jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-'); ?></div>
                            </div>
                        </td>
                        <td class="py-3">
                            <?php if($jadwal->status == 'Menunggu'): ?>
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">Menunggu</span>
                            <?php elseif($jadwal->status == 'Berlangsung'): ?>
                                <span class="badge bg-primary" style="font-size: 0.7rem;">Berlangsung</span>
                            <?php elseif($jadwal->status == 'Selesai'): ?>
                                <span class="badge bg-success" style="font-size: 0.7rem;">Selesai</span>
                            <?php elseif($jadwal->status == 'Dibatalkan'): ?>
                                <span class="badge bg-danger" style="font-size: 0.7rem;">Dibatalkan</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3">
                            <div class="small">
                                <div class="text-dark"><i class="bi bi-images me-1 text-muted"></i><?php echo e($jadwal->photos->count()); ?> foto</div>
                                <?php if($jadwal->catatan): ?>
                                    <div class="text-secondary text-truncate small" style="max-width: 140px;" title="<?php echo e($jadwal->catatan); ?>"><?php echo e($jadwal->catatan); ?></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-3 text-center px-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?php echo e(route('petugas.upload-foto', $jadwal->id)); ?>" class="btn btn-success btn-sm fw-semibold" style="border-radius: 6px; font-size: 0.75rem;">
                                    <i class="bi bi-camera me-1"></i> Upload
                                </a>
                                <?php if($jadwal->status != 'Selesai'): ?>
                                <a href="<?php echo e(route('petugas.inspeksi.create', $jadwal->fasilitas_id)); ?>" class="btn btn-outline-primary btn-sm fw-semibold" style="border-radius: 6px; font-size: 0.75rem;">
                                    <i class="bi bi-pencil-square me-1"></i> Isi
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/petugas/jadwal.blade.php ENDPATH**/ ?>