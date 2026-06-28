<!DOCTYPE html>
<html lang="en" data-coreui-theme="light">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="SanitaCHECK Admin Panel">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> - SanitaCHECK</title>
    
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo e(asset('assets/admin/dist/assets/favicon/android-icon-192x192.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('assets/admin/dist/assets/favicon/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(asset('assets/admin/dist/assets/favicon/favicon-96x96.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('assets/admin/dist/assets/favicon/favicon-16x16.png')); ?>">
    
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@6.3.3/dist/simplebar.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/dist/css/vendors/simplebar.css')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Main styles for this application-->
    <link href="<?php echo e(asset('assets/admin/dist/css/style.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('assets/admin/dist/js/config.js')); ?>"></script>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
  </head>
  <body>
    <!-- Sidebar -->
    <?php echo $__env->make('layouts.admin.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <div class="wrapper d-flex flex-column min-vh-100">
      <!-- Header -->
      <?php echo $__env->make('layouts.admin.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      
      <!-- Main Content -->
      <div class="body flex-grow-1">
        <div class="container-lg px-4">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
      </div>
      
      <!-- Footer -->
      <?php echo $__env->make('layouts.admin.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
          <div class="modal-body text-center p-4">
            <div class="mb-3">
              <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                <svg style="width: 30px; height: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>
              </div>
            </div>
            <h6 class="fw-bold mb-2">Konfirmasi Hapus</h6>
            <p class="text-body-secondary small mb-4">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="d-flex justify-content-center gap-2">
              <button type="button" class="btn btn-light fw-bold flex-grow-1" data-coreui-dismiss="modal" style="border-radius: 8px;">Batal</button>
              <form id="deleteForm" action="" method="POST" class="m-0 flex-grow-1 d-flex">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger text-white fw-bold shadow-sm flex-grow-1" style="border-radius: 8px;">Ya, Hapus</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CoreUI and necessary plugins-->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.6.1/dist/js/coreui.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simplebar@6.3.3/dist/simplebar.min.js"></script>
    <script>
      // Set current date in header
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      const today = new Date();
      const dateElement = document.getElementById('currentDate');
      if (dateElement) {
        dateElement.textContent = today.toLocaleDateString('id-ID', options);
      }

      const header = document.querySelector("header.header");
      document.addEventListener("scroll", () => {
        if (header) {
          header.classList.toggle("shadow-sm", document.documentElement.scrollTop > 0);
        }
      });
      
      function confirmDelete(url) {
        document.getElementById('deleteForm').action = url;
        var deleteModal = new coreui.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();
      }

      function confirmMarkComplete(url) {
        if (confirm('Apakah Anda yakin ingin menandai tugas ini sebagai selesai?')) {
          var form = document.createElement('form');
          form.method = 'POST';
          form.action = url;
          
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
          
          document.body.appendChild(form);
          form.submit();
        }
      }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
  </body>
</html>
<?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/layouts/admin.blade.php ENDPATH**/ ?>