<?php $__env->startSection('title', 'Jadwal Inspeksi | SanitaCheck'); ?>

<?php $__env->startSection('content'); ?>
<h5 class="fw-bold mb-3"><i class="bi bi-calendar2-check text-success me-2"></i>Jadwal Inspeksi</h5>

<?php if($jadwalDitugaskan->isEmpty()): ?>
<div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
    <div class="card-body">
        <i class="bi bi-calendar-x text-secondary" style="font-size: 3rem;"></i>
        <p class="text-secondary mt-2 mb-0">Belum ada jadwal inspeksi saat ini.</p>
    </div>
</div>
<?php else: ?>
<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0">
                <thead style="border-bottom: 1px solid #dee2e6; background-color: #f8f9fa;">
                    <tr>
                        <th class="py-3 px-3" style="font-size: 0.8rem; font-weight: 600;">No</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Jenis Tugas</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Fasilitas</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Tanggal</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Status</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Inspeksi</th>
                        <th class="py-3 text-center px-3" style="font-size: 0.8rem; font-weight: 600;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $jadwalDitugaskan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td class="py-3 px-3"><?php echo e($index + 1); ?></td>
                        <td class="py-3">
                            <div class="fw-semibold text-dark"><?php echo e($jadwal->jenis_tugas); ?></div>
                        </td>
                        <td class="py-3">
                            <div>
                                <div class="fw-semibold text-dark"><?php echo e($jadwal->fasilitas->nama_fasilitas ?? '-'); ?></div>
                                <div class="text-secondary small"><?php echo e($jadwal->fasilitas->lokasi ?? '-'); ?></div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="small">
                                <div><?php echo e($jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-'); ?></div>
                                <div class="text-secondary">s/d <?php echo e($jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-'); ?></div>
                            </div>
                        </td>
                        <td class="py-3">
                            <?php if($jadwal->status == 'Menunggu'): ?>
                            <span class="badge bg-secondary" style="font-size: 0.7rem;">Menunggu</span>
                            <?php elseif($jadwal->status == 'Berlangsung'): ?>
                            <span class="badge bg-primary" style="font-size: 0.7rem;">Berlangsung</span>
                            <?php elseif($jadwal->status == 'Selesai'): ?>
                            <span class="badge bg-success" style="font-size: 0.7rem;">Selesai</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3">
                            <div class="small">
                                <div><i class="bi bi-images me-1"></i><?php echo e($jadwal->photos->count()); ?> foto</div>
                                <?php if($jadwal->catatan): ?>
                                <div class="text-secondary text-truncate" style="max-width: 150px;"><?php echo e($jadwal->catatan); ?></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-3 text-center px-3">
                            <a href="<?php echo e(route('petugas.upload-foto', $jadwal->id)); ?>" class="btn btn-success btn-sm fw-semibold" style="border-radius: 6px;">
                                <i class="bi bi-camera me-1"></i> Upload
                            </a>
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