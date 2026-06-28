<?php $__env->startSection('title', 'Profil | SanitaCheck'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <h5 class="fw-bold mb-1"><i class="bi bi-person-fill text-success me-2"></i>Profil Saya</h5>
</div>

<div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
    <div class="card-body p-3">
        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
            <div class="flex-shrink-0 me-3">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 55px; height: 55px; font-size: 1.5rem;">
                    <?php echo e(strtoupper(substr($petugasData->nama_petugas ?? $user->name, 0, 1))); ?>

                </div>
            </div>
            <div class="text-truncate">
                <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.95rem;"><?php echo e($petugasData->nama_petugas ?? $user->name); ?></h6>
                <div class="text-secondary" style="font-size: 0.75rem; line-height: 1.4;">
                    <div class="text-truncate"><i class="bi bi-person-badge me-1"></i><?php echo e(ucfirst($user->role)); ?></div>
                    <div class="text-truncate"><i class="bi bi-envelope me-1"></i><?php echo e($petugasData->email ?? $user->email ?? '-'); ?></div>
                    <?php if($petugasData && $petugasData->no_hp): ?>
                        <div class="text-truncate"><i class="bi bi-phone me-1"></i><?php echo e($petugasData->no_hp); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div>
            <span class="text-dark fw-bold d-block mb-2" style="font-size: 0.75rem;"><i class="bi bi-bar-chart-line text-success me-1"></i>Statistik Kerja</span>
            <div class="row g-2 text-center">
                <div class="col-4">
                    <div class="bg-success bg-opacity-10 py-2 px-1" style="border-radius: 8px;">
                        <div class="fw-bold text-success lh-1 mb-1" style="font-size: 1.1rem;"><?php echo e($totalInspeksi); ?></div>
                        <div class="text-secondary" style="font-size: 0.65rem;">Total Inspeksi</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-primary bg-opacity-10 py-2 px-1" style="border-radius: 8px;">
                        <div class="fw-bold text-primary lh-1 mb-1" style="font-size: 1.1rem;"><?php echo e($totalJadwal); ?></div>
                        <div class="text-secondary" style="font-size: 0.65rem;">Total Jadwal</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-warning bg-opacity-10 py-2 px-1" style="border-radius: 8px;">
                        <div class="fw-bold text-warning lh-1 mb-1" style="font-size: 1.1rem;"><?php echo e($jadwalSelesai); ?></div>
                        <div class="text-secondary" style="font-size: 0.65rem;">Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
    <div class="card-body p-3">
        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;"><i class="bi bi-key me-2 text-success"></i>Ubah Password</h6>
        
        <form action="<?php echo e(route('petugas.updatePassword')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1 text-secondary fw-semibold" style="font-size: 0.75rem;">Password Lama</label>
                    <input type="password" name="password_lama" class="form-control form-control-sm" placeholder="Password lama" required style="border-radius: 6px; font-size: 0.8rem;">
                    <?php $__errorArgs = ['password_lama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1" style="font-size: 0.7rem;"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1 text-secondary fw-semibold" style="font-size: 0.75rem;">Password Baru</label>
                    <input type="password" name="password_baru" class="form-control form-control-sm" placeholder="Min. 8 karakter" required style="border-radius: 6px; font-size: 0.8rem;">
                    <?php $__errorArgs = ['password_baru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1" style="font-size: 0.7rem;"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1 text-secondary fw-semibold" style="font-size: 0.75rem;">Konfirmasi Password</label>
                    <input type="password" name="password_baru_confirmation" class="form-control form-control-sm" placeholder="Ketik ulang" required style="border-radius: 6px; font-size: 0.8rem;">
                </div>
                
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-success btn-sm w-100 fw-bold py-2" style="border-radius: 8px; background-color: #1b5e3a; border-color: #1b5e3a; font-size: 0.8rem;">
                        <i class="bi bi-save me-1"></i> Simpan Password
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div>
    <form action="<?php echo e(route('logout')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-sm btn-outline-danger w-100 fw-bold py-2" style="border-radius: 8px; font-size: 0.8rem;">
            <i class="bi bi-box-arrow-right me-1"></i> Logout Akun
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/petugas/profil.blade.php ENDPATH**/ ?>