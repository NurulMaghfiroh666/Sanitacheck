<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="SanitaCHECK Admin Panel">
    <title>@yield('title', 'Admin Panel') - SanitaCHECK</title>
    
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/admin/dist/assets/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/admin/dist/assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/admin/dist/assets/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/admin/dist/assets/favicon/favicon-16x16.png') }}">
    
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@6.3.3/dist/simplebar.min.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/dist/css/vendors/simplebar.css') }}">
    <!-- Main styles for this application-->
    <link href="{{ asset('assets/admin/dist/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/admin/dist/js/config.js') }}"></script>
    <script src="{{ asset('assets/admin/dist/js/color-modes.js') }}"></script>
    
    @stack('styles')
  </head>
  <body>
    <!-- Sidebar -->
    @include('layouts.admin.sidebar')
    
    <div class="wrapper d-flex flex-column min-vh-100">
      <!-- Header -->
      @include('layouts.admin.header')
      
      <!-- Main Content -->
      <div class="body flex-grow-1">
        <div class="container-lg px-4">
            @yield('content')
        </div>
      </div>
      
      <!-- Footer -->
      @include('layouts.admin.footer')
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
                @csrf
                @method('DELETE')
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
    </script>
    @stack('scripts')
  </body>
</html>
