<?php $__env->startSection('title', 'Kelola Petugas'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-2 d-flex justify-content-between align-items-center border-bottom pb-1">
    <div>
        <h5 class="fw-bold mb-0" style="color: #1b5e3a;">Kelola Petugas</h5>
        <div class="text-body-secondary mt-1" style="font-size: 0.75rem;">
            <span>Dashboard</span> <span class="mx-1">/</span> <span>Kelola Petugas</span>
        </div>
    </div>
    <button type="button" class="btn btn-success text-white fw-bold btn-sm shadow-sm px-2 d-flex align-items-center" style="border-radius: 4px; background-color: #1b5e3a; border-color: #1b5e3a; font-size: 0.75rem;" data-coreui-toggle="modal" data-coreui-target="#addPetugasModal">
        + Tambah Petugas
    </button>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
    <?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if($errors->any()): ?>
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
    <ul class="mb-0">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card border shadow-sm mb-4" style="border-radius: 10px;">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-1 px-3">
        <h6 class="mb-0 fw-bold text-success" style="font-size: 0.8rem;"><i class="bi bi-people me-1"></i>Daftar Petugas</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-borderless table-hover align-middle mb-0" style="font-size: 0.75rem;">
                <thead style="border-bottom: 1px solid var(--cui-border-color); background-color: var(--cui-tertiary-bg);">
                    <tr>
                        <th class="text-center text-success py-1 px-2" style="width: 40px;">No</th>
                        <th class="text-success py-1">Nama & Username</th>
                        <th class="text-success py-1">Kontak</th>
                        <th class="text-center text-success py-1" style="width: 80px;">Status</th>
                        <th class="text-center text-success py-1 px-2" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $petugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center text-body-secondary py-1 px-2"><?php echo e($index + 1); ?></td>
                        <td class="py-1">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 26px; height: 26px; font-size: 0.7rem;">
                                    <?php echo e(strtoupper(substr($item->nama_petugas ?? 'P', 0, 1))); ?>

                                </div>
                                <div>
                                    <div class="fw-bold text-truncate" style="max-width: 150px; color: #333;"><?php echo e($item->nama_petugas); ?></div>
                                    <div class="text-body-secondary text-truncate" style="max-width: 150px; font-size: 0.7rem;"><?php echo e('@' . ($item->user->username ?? '-')); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-1">
                            <div class="fw-semibold text-truncate" style="max-width: 180px; color: #333;"><?php echo e($item->email); ?></div>
                            <div class="text-body-secondary" style="font-size: 0.7rem;"><?php echo e($item->no_hp); ?></div>
                        </td>
                        <td class="text-center py-1">
                            <?php if($item->status_aktif): ?>
                            <span class="badge bg-success bg-opacity-10 text-success fw-normal" style="font-size: 0.7rem;">Aktif</span>
                            <?php else: ?>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal" style="font-size: 0.7rem;">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center py-1 px-2">
                            <div class="btn-group btn-group-sm shadow-sm" role="group">
                                <button type="button" class="btn btn-light border p-1" title="Edit"
                                    data-id="<?php echo e($item->id); ?>"
                                    data-nama="<?php echo e($item->nama_petugas); ?>"
                                    data-email="<?php echo e($item->email); ?>"
                                    data-nohp="<?php echo e($item->no_hp); ?>"
                                    data-status="<?php echo e($item->status_aktif); ?>"
                                    onclick="openEditModal(this)">
                                    <svg style="width: 12px; height: 12px; color: #0d6efd;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 5.4 506.5s12.5 12.3 21.2 12.3h120.3c10.9 0 21.4-4.3 29.2-12l233.4-233.4L291.7 90.3z"/></svg>
                                </button>
                                <button type="button" class="btn btn-light border p-1" title="Hapus" onclick="confirmDelete('<?php echo e(route('petugas.destroy', $item->id)); ?>')">
                                    <svg style="width: 12px; height: 12px; color: #dc3545;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.4 467c1.6 25.3 22.6 45 47.9 45H346.7c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data petugas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addPetugasModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" style="color: #1b5e3a;">Tambah Petugas Baru</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo e(route('petugas.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-body text-start">
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">Nama Petugas</label>
                <input type="text" name="nama_petugas" class="form-control" placeholder="Masukkan nama lengkap" required style="border-radius: 6px;">
                <div class="form-text small text-body-secondary">Username akan dibuat otomatis dari nama (tanpa spasi)</div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">Email</label>
                <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required style="border-radius: 6px;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">No. HP</label>
                <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required style="border-radius: 6px;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">Password</label>
                <input type="text" name="password" class="form-control" value="petugas123" readonly style="border-radius: 6px; background-color: #f8f9fa;">
                <div class="form-text small text-body-secondary">Password default: petugas123</div>
            </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
            <button type="button" class="btn btn-outline-secondary btn-sm fw-bold" data-coreui-dismiss="modal" style="border-radius: 6px;">Batal</button>
            <button type="submit" class="btn btn-success btn-sm fw-bold shadow-sm" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a;">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editPetugasModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" style="color: #1b5e3a;">Edit Petugas</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editPetugasForm" action="" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="modal-body text-start">
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">Nama Petugas</label>
                <input type="text" name="nama_petugas" id="edit_nama_petugas" class="form-control" placeholder="Masukkan nama lengkap" required style="border-radius: 6px;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">Email</label>
                <input type="email" name="email" id="edit_email" class="form-control" placeholder="contoh@email.com" required style="border-radius: 6px;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">No. HP</label>
                <input type="text" name="no_hp" id="edit_no_hp" class="form-control" placeholder="08xxxxxxxxxx" required style="border-radius: 6px;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">Status</label>
                <select name="status_aktif" id="edit_status_aktif" class="form-select" required style="border-radius: 6px;">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
            <button type="button" class="btn btn-outline-secondary btn-sm fw-bold" data-coreui-dismiss="modal" style="border-radius: 6px;">Batal</button>
            <button type="submit" class="btn btn-success btn-sm fw-bold shadow-sm" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a;">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function openEditModal(btn) {
    var id = btn.getAttribute('data-id');
    var nama = btn.getAttribute('data-nama');
    var email = btn.getAttribute('data-email');
    var nohp = btn.getAttribute('data-nohp');
    var status = btn.getAttribute('data-status');

    document.getElementById('editPetugasForm').action = '<?php echo e(url('petugas')); ?>/' + id;
    document.getElementById('edit_nama_petugas').value = nama;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_no_hp').value = nohp;
    document.getElementById('edit_status_aktif').value = status;

    var modal = new coreui.Modal(document.getElementById('editPetugasModal'));
    modal.show();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/admin/petugas.blade.php ENDPATH**/ ?>