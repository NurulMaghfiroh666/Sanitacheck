<!DOCTYPE html>
<html lang="en">
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
    <!-- Vendors styles-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@6.3.3/dist/simplebar.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/dist/css/vendors/simplebar.css')); ?>">
    <!-- Main styles for this application-->
    <link href="<?php echo e(asset('assets/admin/dist/css/style.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('assets/admin/dist/js/config.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/admin/dist/js/color-modes.js')); ?>"></script>
    
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
    
    <!-- CoreUI and necessary plugins-->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.6.1/dist/js/coreui.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simplebar@6.3.3/dist/simplebar.min.js"></script>
    <script>
      const header = document.querySelector("header.header");
      document.addEventListener("scroll", () => {
        if (header) {
          header.classList.toggle("shadow-sm", document.documentElement.scrollTop > 0);
        }
      });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
  </body>
</html>
<?php /**PATH C:\Project Web Porto\SanitaCheck - Kampus Sehat Terintegrasi\SanitaCHECK-version1-kelompok-6\resources\views/layouts/admin.blade.php ENDPATH**/ ?>