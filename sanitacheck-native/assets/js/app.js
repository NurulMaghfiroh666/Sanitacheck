document.addEventListener('DOMContentLoaded', function() {
    // Navbar background solid saat scroll
    var navbar = document.getElementById('mainNavbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            navbar.style.backgroundColor = window.scrollY > 50 ? '#13462a' : '#1a5c38';
        });
    }

    // Tutup alert sukses via tombol close
    var successAlert = document.getElementById('success-alert');
    if (successAlert) {
        var closeBtn = successAlert.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                successAlert.style.display = 'none';
            });
        }
    }

    // Validasi dan kirim form lapor
    var formLapor = document.getElementById('form-lapor');
    if (formLapor) {
        formLapor.addEventListener('submit', function(e) {
            e.preventDefault();

            var fasilitas = document.getElementById('fasilitas').value;
            var catatan = document.getElementById('catatan').value;
            var persetujuan = document.getElementById('persetujuan').checked;

            var isValid = true;
            var errorMessage = '';

            if (!fasilitas) {
                isValid = false;
                errorMessage += '- Fasilitas wajib dipilih.\n';
            }

            if (catatan.trim().length < 10) {
                isValid = false;
                errorMessage += '- Catatan minimal 10 karakter.\n';
            }

            if (!persetujuan) {
                isValid = false;
                errorMessage += '- Anda harus mencentang kotak pernyataan.\n';
            }

            if (!isValid) {
                alert('Terdapat kesalahan pada form:\n' + errorMessage);
                return;
            }

            var btnSubmit = document.getElementById('btn-submit');
            var originalText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
            btnSubmit.disabled = true;

            // Upload foto jika ada
            var fotoFile = document.getElementById('foto').files[0];
            var payload = {
                fasilitas_id: parseInt(fasilitas),
                nama_pelapor: document.getElementById('nama_pelapor').value || 'Anonim',
                catatan: catatan.trim()
            };

            // Kirim via AJAX ke API
            fetch('services/api.php?action=lapor', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(function(response) { return response.json(); })
            .then(function() {
                tampilkanSukses(formLapor, btnSubmit, originalText);
            })
            .catch(function() {
                tampilkanSukses(formLapor, btnSubmit, originalText);
            });
        });
    }

    function tampilkanSukses(form, btn, originalText) {
        var alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.display = 'block';
            alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            alert('Laporan berhasil dikirim. Terima kasih atas partisipasi Anda.');
        }
        form.reset();
        // Reset camera box
        if (typeof resetCameraBox === 'function') {
            resetCameraBox();
        }
        btn.innerHTML = originalText;
        btn.disabled = false;
    }

    // Smooth scroll untuk anchor internal di halaman lapor
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
