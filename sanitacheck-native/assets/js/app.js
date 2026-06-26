document.addEventListener('DOMContentLoaded', function() {
    var navbar = document.getElementById('mainNavbar');
    var currentPage = window.location.pathname.split('/').pop() || 'beranda-user.php';

    // ============ NAVBAR SCROLL ============
    if (navbar) {
        window.addEventListener('scroll', function() {
            navbar.style.backgroundColor = window.scrollY > 50 ? '#13462a' : '#1a5c38';
        });
    }

    // ============ SEARCH BAR ============
    var searchInput = document.getElementById('navbarSearch');
    var searchResults = document.getElementById('searchResults');
    var searchTimer = null;
    var allFacilities = [];

    // Load facility data once
    function loadFacilities() {
        var existing = document.getElementById('facility-data-store');
        if (existing) {
            try { allFacilities = JSON.parse(existing.textContent); } catch(e) {}
            return;
        }
        // Fetch via API
        fetch('services/api.php?action=data&type=fasilitas')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'success') allFacilities = data.data || [];
            })
            .catch(function() {});
    }
    loadFacilities();

    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            var q = this.value.trim().toLowerCase();
            if (q.length < 1) { searchResults.classList.remove('show'); return; }

            searchTimer = setTimeout(function() {
                var filtered = allFacilities.filter(function(f) {
                    var nama = (f.nama || '').toLowerCase();
                    var lokasi = (f.lokasi || '').toLowerCase();
                    return nama.indexOf(q) !== -1 || lokasi.indexOf(q) !== -1;
                }).slice(0, 8);

                if (filtered.length === 0) {
                    searchResults.innerHTML = '<div class="search-result-item" style="cursor:default;color:#999;">Tidak ditemukan</div>';
                } else {
                    var html = '';
                    filtered.forEach(function(f) {
                        var statusClass = 'bg-warning text-dark';
                        var statusText = f.status || '-';
                        var stl = (statusText + '').toLowerCase();
                        if (stl.indexOf('bersih') !== -1) { statusClass = 'bg-success text-white'; }
                        else if (stl.indexOf('buruk') !== -1 || stl.indexOf('rusak') !== -1) { statusClass = 'bg-danger text-white'; }
                        else if (stl.indexOf('dibersihkan') !== -1) { statusClass = 'bg-warning text-dark'; }

                        var iconMap = { 'toilet':'bi-gender-ambiguous','wastafel':'bi-droplet','kantin':'bi-shop','lobby':'bi-building','kantor':'bi-person-workspace' };
                        var jenis = (f.jenis || '').toLowerCase();
                        var icon = iconMap[jenis] || 'bi-building';
                        var iconBg = { 'toilet':'#1a5c38','wastafel':'#3b82f6','kantin':'#86efac','lobby':'#fef08a','kantor':'#fecaca' };
                        var bg = iconBg[jenis] || '#e5e7eb';

                        html += '<a href="detail-fasilitas-user.php?id=' + (f.id || '') + '" class="search-result-item">' +
                            '<span class="result-icon" style="background:' + bg + '"><i class="bi ' + icon + '"></i></span>' +
                            '<div><div class="result-name">' + escapeHtml(f.nama || '') + '</div><div class="result-lokasi">' + escapeHtml(f.lokasi || '') + '</div></div>' +
                            '<span class="badge ' + statusClass + ' result-status">' + escapeHtml(statusText) + '</span>' +
                        '</a>';
                    });
                    searchResults.innerHTML = html;

                    // Click on result: if on beranda, filter table; else navigate
                    searchResults.querySelectorAll('.search-result-item').forEach(function(item) {
                        item.addEventListener('click', function(e) {
                            if (currentPage === 'beranda-user.php' || currentPage === '') {
                                e.preventDefault();
                                var name = this.querySelector('.result-name');
                                if (name) {
                                    filterTableBySearch(name.textContent);
                                    document.getElementById('fasilitas-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }
                                searchResults.classList.remove('show');
                                searchInput.value = name ? name.textContent : '';
                            }
                        });
                    });
                }
                searchResults.classList.add('show');
            }, 250);
        });

        // Close dropdown on blur
        searchInput.addEventListener('blur', function() {
            setTimeout(function() { searchResults.classList.remove('show'); }, 200);
        });
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0) searchResults.classList.add('show');
        });
    }

    function filterTableBySearch(keyword) {
        var tableBody = document.querySelector('#fasilitas-table tbody');
        if (!tableBody) return;
        var rows = tableBody.querySelectorAll('tr');
        var found = false;
        rows.forEach(function(row) {
            if (row.id === 'filter-no-result') { row.style.display = 'none'; return; }
            var nameCell = row.querySelector('td:first-child');
            if (nameCell) {
                var match = nameCell.textContent.toLowerCase().indexOf(keyword.toLowerCase()) !== -1;
                row.style.display = match ? '' : 'none';
                if (match) found = true;
            }
        });
        var noResult = document.getElementById('filter-no-result');
        if (!found && keyword) {
            if (!noResult) {
                noResult = document.createElement('tr');
                noResult.id = 'filter-no-result';
                noResult.innerHTML = '<td colspan="4" class="text-center py-4 text-muted fw-medium">Tidak ada fasilitas yang cocok dengan pencarian "' + escapeHtml(keyword) + '".</td>';
                tableBody.appendChild(noResult);
            }
            noResult.style.display = '';
        } else if (noResult) {
            noResult.style.display = 'none';
        }
        // Reset active state on category cards
        document.querySelectorAll('.category-card').forEach(function(c) { c.classList.remove('active-filter'); });
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    // ============ DARK MODE ============
    var themeToggle = document.getElementById('themeToggle');
    var themeIcon = document.getElementById('themeIcon');
    var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    var savedTheme = localStorage.getItem('sanitacheck-theme');

    function setTheme(isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark-mode');
            if (themeIcon) { themeIcon.className = 'bi bi-sun-fill theme-icon'; }
            if (themeToggle) themeToggle.title = 'Mode Terang';
        } else {
            document.documentElement.classList.remove('dark-mode');
            if (themeIcon) { themeIcon.className = 'bi bi-moon-fill theme-icon'; }
            if (themeToggle) themeToggle.title = 'Mode Gelap';
        }
        localStorage.setItem('sanitacheck-theme', isDark ? 'dark' : 'light');
    }

    // Apply saved or system preference
    if (savedTheme) {
        setTheme(savedTheme === 'dark');
    } else {
        setTheme(prefersDark);
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            var isDark = !document.documentElement.classList.contains('dark-mode');
            setTheme(isDark);
        });
    }

    // ============ ALERT CLOSE ============
    var successAlert = document.getElementById('success-alert');
    if (successAlert) {
        var closeBtn = successAlert.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() { successAlert.style.display = 'none'; });
        }
    }

    // ============ FORM LAPOR ============
    var formLapor = document.getElementById('form-lapor');
    if (formLapor) {
        formLapor.addEventListener('submit', function(e) {
            e.preventDefault();

            var fasilitas = document.getElementById('fasilitas').value;
            var catatan = document.getElementById('catatan').value;
            var persetujuan = document.getElementById('persetujuan').checked;

            var isValid = true;
            var errorMessage = '';

            if (!fasilitas) { isValid = false; errorMessage += '- Fasilitas wajib dipilih.\n'; }
            if (catatan.trim().length < 10) { isValid = false; errorMessage += '- Catatan minimal 10 karakter.\n'; }
            if (!persetujuan) { isValid = false; errorMessage += '- Anda harus mencentang kotak pernyataan.\n'; }

            if (!isValid) { alert('Terdapat kesalahan pada form:\n' + errorMessage); return; }

            var btnSubmit = document.getElementById('btn-submit');
            var originalText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Mengirim...';
            btnSubmit.disabled = true;

            var payload = {
                fasilitas_id: parseInt(fasilitas),
                nama_pelapor: document.getElementById('nama_pelapor').value || 'Anonim',
                catatan: catatan.trim()
            };

            fetch('services/api.php?action=lapor', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(function(r) { return r.json(); })
            .then(function() { tampilkanSukses(formLapor, btnSubmit, originalText); })
            .catch(function() { tampilkanSukses(formLapor, btnSubmit, originalText); });
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
        if (typeof resetCameraBox === 'function') resetCameraBox();
        btn.innerHTML = originalText;
        btn.disabled = false;
    }

    // ============ SMOOTH SCROLL ============
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });
});