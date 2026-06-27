<?php
require_once 'services/api.php';

$fasilitas = getFasilitas()['data'];
$keluhan = getKeluhan()['data'];
$statistik = getStatistik();

$total_fasilitas = count($fasilitas);
$total_laporan = count($keluhan);
$selesai = count(array_filter($keluhan, fn($k) => stripos($k['status'] ?? '', 'selesai') !== false));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - SanitaCheck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css?v=12">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: #1a5c38 !important; padding-top: 1rem; padding-bottom: 1rem; }
        .navbar form.nav-search-form { display: none !important; }
        a.text-decoration-none:hover { text-decoration: underline !important; }

        .faq-hero {
            background: linear-gradient(135deg, #1a5c38 0%, #0d3b1e 100%);
            color: white;
            padding: 60px 0 50px;
            text-align: center;
        }
        .faq-hero h1 { font-weight: 700; font-size: 2rem; margin-bottom: 8px; }
        .faq-hero p { opacity: 0.85; font-size: 1rem; max-width: 600px; margin: 0 auto; }

        .faq-section {
            margin-top: -20px;
            padding-bottom: 60px;
        }
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .faq-item {
            background: white;
            border-radius: 12px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .faq-item:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .faq-question {
            padding: 18px 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            user-select: none;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            font-size: 0.95rem;
            font-weight: 600;
            color: #212529;
            transition: background 0.15s;
        }
        .faq-question:hover { background: #f8f9fa; }
        .faq-question .faq-icon {
            font-size: 1rem;
            color: #1a5c38;
            transition: transform 0.25s;
            flex-shrink: 0;
        }
        .faq-item.open .faq-icon {
            transform: rotate(180deg);
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        .faq-item.open .faq-answer {
            max-height: 500px;
        }
        .faq-answer-inner {
            padding: 0 24px 18px;
            font-size: 0.9rem;
            color: #495057;
            line-height: 1.7;
        }
        .faq-answer-inner a { color: #1a5c38; font-weight: 500; }
        .faq-answer-inner ul { padding-left: 20px; margin-bottom: 0; }
        .faq-answer-inner li { margin-bottom: 4px; }

        .faq-stats-bar {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 28px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #e9ecef;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .faq-stat-item { text-align: center; min-width: 100px; }
        .faq-stat-item .num { font-size: 1.5rem; font-weight: 700; color: #1a5c38; }
        .faq-stat-item .lbl { font-size: 0.75rem; color: #6c757d; margin-top: 2px; }

        @media (max-width: 768px) {
            .faq-hero { padding: 40px 0 32px; }
            .faq-hero h1 { font-size: 1.4rem; }
            .faq-hero p { font-size: 0.85rem; }
            .faq-question { padding: 14px 16px; font-size: 0.85rem; }
            .faq-answer-inner { padding: 0 16px 14px; font-size: 0.82rem; }
            .faq-stats-bar { padding: 14px 16px; gap: 12px; }
            .faq-stat-item .num { font-size: 1.2rem; }
        }

        /* DARK MODE */
        html.dark-mode .faq-hero { background: linear-gradient(135deg, #0b2816 0%, #061a0e 100%); }
        html.dark-mode .faq-item { background: #212326; border-color: #323438; }
        html.dark-mode .faq-item:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.3); }
        html.dark-mode .faq-question { color: #e4e6eb; }
        html.dark-mode .faq-question:hover { background: #282a2d; }
        html.dark-mode .faq-question .faq-icon { color: #6fcf97; }
        html.dark-mode .faq-answer-inner { color: #b0b3b8; }
        html.dark-mode .faq-answer-inner a { color: #6fcf97; }
        html.dark-mode .faq-stats-bar { background: #212326; border-color: #323438; }
        html.dark-mode .faq-stat-item .num { color: #6fcf97; }
        html.dark-mode .faq-stat-item .lbl { color: #8a8d91; }
    </style>
</head>
<body>

<?php include 'components/navbar.php'; ?>

<div class="faq-hero">
    <div class="container">
        <h1><i class="bi bi-question-circle-fill me-2"></i>Pertanyaan Umum (FAQ)</h1>
        <p>Temukan jawaban atas pertanyaan seputar SanitaCheck, cara pelaporan, dan proses penanganan fasilitas.</p>
    </div>
</div>

<div class="faq-section">
    <div class="container faq-container">

        <!-- Statistik ringkas -->
        <div class="faq-stats-bar">
            <div class="faq-stat-item">
                <div class="num"><?= $total_fasilitas ?></div>
                <div class="lbl">Fasilitas Dipantau</div>
            </div>
            <div class="faq-stat-item">
                <div class="num"><?= $total_laporan ?></div>
                <div class="lbl">Laporan Masuk</div>
            </div>
            <div class="faq-stat-item">
                <div class="num"><?= $selesai ?></div>
                <div class="lbl">Selesai Ditangani</div>
            </div>
            <div class="faq-stat-item">
                <div class="num"><?= $statistik['persentase_kebersihan'] ?? 0 ?>%</div>
                <div class="lbl">Indeks Kebersihan</div>
            </div>
        </div>

        <!-- Daftar FAQ -->
        <div class="faq-item open">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Apa itu SanitaCheck?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    <strong>SanitaCheck</strong> adalah sistem monitoring kebersihan fasilitas berbasis digital yang dikelola oleh <strong>ITSK Soepraoen Malang</strong>. Platform ini memungkinkan civitas akademika untuk melaporkan kondisi fasilitas yang kurang bersih, memantau status penanganan, dan melihat data kebersihan secara real-time. SanitaCheck hadir sebagai solusi untuk mewujudkan lingkungan kampus yang bersih, sehat, dan nyaman.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Bagaimana cara melaporkan fasilitas yang kotor atau rusak?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Anda dapat melaporkan kondisi fasilitas melalui beberapa langkah mudah:
                    <ol class="mt-2 mb-0">
                        <li>Klik menu <strong>"Lapor"</strong> pada navigasi di atas.</li>
                        <li>Pilih <strong>fasilitas</strong> yang ingin Anda laporkan dari daftar yang tersedia.</li>
                        <li>Isi <strong>nama pelapor</strong> (boleh kosong jika ingin anonim).</li>
                        <li>Tulis <strong>catatan keluhan</strong> secara jelas (minimal 10 karakter).</li>
                        <li>Centang pernyataan bahwa laporan sesuai kondisi sebenarnya.</li>
                        <li>Klik tombol <strong>"Kirim Laporan"</strong>.</li>
                    </ol>
                    Laporan Anda akan langsung masuk ke sistem dan diteruskan ke petugas sanitasi untuk ditindaklanjuti.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah saya bisa melapor tanpa mencantumkan nama?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    <strong>Ya, bisa.</strong> Kolom nama pelapor bersifat opsional. Jika dikosongkan, laporan akan tercatat sebagai <strong>"Anonim"</strong>. Namun, dengan mencantumkan nama, petugas dapat menghubungi Anda jika memerlukan klarifikasi atau informasi tambahan mengenai kondisi fasilitas yang dilaporkan.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Fasilitas apa saja yang bisa dilaporkan?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    SanitaCheck memantau berbagai fasilitas di lingkungan kampus ITSK Soepraoen, antara lain:
                    <ul class="mt-2">
                        <li><strong>Toilet</strong> — Kebersihan toilet, ketersediaan air, dan bau.</li>
                        <li><strong>Wastafel</strong> — Ketersediaan sabun, air mengalir, dan kebersihan wastafel.</li>
                        <li><strong>Kantin</strong> — Kebersihan area makan, meja, dan pembuangan sampah.</li>
                        <li><strong>Lobby / Ruang Tunggu</strong> — Kebersihan lantai, kursi, dan area umum.</li>
                        <li><strong>Kantor / Ruang Kerja</strong> — Kebersihan ruangan dan fasilitas pendukung.</li>
                    </ul>
                    Semua jenis fasilitas ini tersedia dalam pilihan saat Anda membuat laporan.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Bagaimana cara mengetahui status laporan saya?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Status laporan dapat dilihat pada halaman <strong>"Lapor"</strong> di bagian <strong>"Riwayat Laporan Detail"</strong>. Setiap laporan memiliki salah satu dari tiga status berikut:
                    <ul class="mt-2">
                        <li><span style="color: #856404;">&#9679; <strong>Diajukan</strong></span> — Laporan baru masuk dan menunggu diverifikasi petugas.</li>
                        <li><span style="color: #004085;">&#9679; <strong>Sedang Diproses</strong></span> — Petugas sedang menangani laporan di lokasi.</li>
                        <li><span style="color: #155724;">&#9679; <strong>Selesai</strong></span> — Masalah telah selesai ditangani.</li>
                    </ul>
                    Anda juga bisa melihat ringkasan jumlah laporan yang sedang diproses dan selesai di bagian atas halaman Lapor.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Berapa lama waktu penanganan laporan?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Waktu penanganan bervariasi tergantung pada kompleksitas masalah dan ketersediaan petugas. Secara umum:
                    <ul class="mt-2">
                        <li><strong>Masalah ringan</strong> (lantai kotor, sabun habis) — ditangani dalam 1×24 jam.</li>
                        <li><strong>Masalah sedang</strong> (wastafel mampet, keran rusak) — ditangani dalam 2–3 hari kerja.</li>
                        <li><strong>Masalah berat</strong> (kerusakan infrastruktur) — ditangani sesuai jadwal perbaikan yang ditentukan.</li>
                    </ul>
                    Tim sanitasi akan selalu berusaha menangani laporan secepat mungkin.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Apa yang dimaksud dengan "Proses Pengerjaan Petugas"?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    <strong>"Proses Pengerjaan Petugas"</strong> adalah fitur yang menampilkan tahapan penanganan laporan secara transparan. Setiap laporan yang sedang atau sudah diproses akan menampilkan tiga indikator tahapan:
                    <ol class="mt-2">
                        <li><strong>Pengajuan Laporan</strong> — Laporan berhasil dikirim ke sistem.</li>
                        <li><strong>Diverifikasi Petugas</strong> — Petugas telah memeriksa dan memvalidasi laporan.</li>
                        <li><strong>Pengerjaan di Lokasi</strong> — Petugas sedang atau sudah melakukan penanganan.</li>
                    </ol>
                    Fitur ini memastikan transparansi proses sehingga pengguna dapat memantau sejauh mana laporan mereka ditindaklanjuti.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Bagaimana cara melihat detail fasilitas?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Anda dapat melihat detail fasilitas dengan cara:
                    <ul class="mt-2">
                        <li>Pada halaman <strong>Beranda</strong>, scroll ke bagian <strong>"Daftar Lengkap Fasilitas"</strong>.</li>
                        <li>Klik nama fasilitas yang ingin Anda lihat detailnya.</li>
                        <li>Atau gunakan <strong>pencarian</strong> di navbar, ketik nama fasilitas, lalu klik hasil yang muncul.</li>
                    </ul>
                    Halaman detail fasilitas menampilkan informasi lengkap seperti lokasi, jenis, status terkini, riwayat inspeksi 3 terakhir, dan rekomendasi penanganan.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Apa arti badge status pada fasilitas?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Setiap fasilitas memiliki badge status yang menandakan kondisi terkini:
                    <ul class="mt-2">
                        <li><span class="badge-custom badge-aman" style="padding: 3px 10px; font-size: 11px;">Bersih dan Aman</span> — Fasilitas dalam kondisi bersih dan layak pakai.</li>
                        <li><span class="badge-custom badge-perhatian" style="padding: 3px 10px; font-size: 11px;">Perlu Perhatian</span> — Ada indikasi awal masalah yang perlu dimonitor.</li>
                        <li><span class="badge-custom badge-dibersihkan" style="padding: 3px 10px; font-size: 11px;">Perlu Dibersihkan</span> — Fasilitas memerlukan pembersihan segera.</li>
                        <li><span class="badge-custom badge-buruk" style="padding: 3px 10px; font-size: 11px;">Buruk</span> — Kondisi fasilitas dalam keadaan buruk dan perlu tindakan.</li>
                    </ul>
                    Status ini diperbarui oleh petugas setelah melakukan inspeksi rutin.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Bagaimana cara mengganti tema tampilan?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    SanitaCheck menyediakan dua mode tampilan: <strong>Terang</strong> (Light Mode) dan <strong>Gelap</strong> (Dark Mode). Untuk menggantinya:
                    <ul class="mt-2">
                        <li>Cari ikon <i class="bi bi-moon-fill" style="color: #1a5c38;"></i> / <i class="bi bi-sun-fill" style="color: #f2c94c;"></i> di pojok kanan atas navbar.</li>
                        <li>Klik ikon tersebut untuk beralih antara mode Terang dan Gelap.</li>
                        <li>Pengaturan tema akan disimpan secara otomatis dan diterapkan di semua halaman.</li>
                    </ul>
                    Mode Gelap membantu mengurangi ketegangan mata saat menggunakan aplikasi di lingkungan dengan cahaya redup.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah data laporan saya aman?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    <strong>Tentu.</strong> SanitaCheck mengutamakan keamanan data pengguna. Seluruh data laporan disimpan secara aman dan hanya digunakan untuk keperluan monitoring kebersihan fasilitas. Data tidak akan dibagikan kepada pihak ketiga tanpa izin. Jika Anda memilih anonim, identitas Anda tidak akan tercantum dalam laporan.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Siapa yang menangani laporan yang saya kirim?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Laporan Anda akan ditangani oleh <strong>Tim Sanitasi Kampus ITSK Soepraoen</strong> yang terdiri dari petugas kebersihan dan teknisi yang berpengalaman. Setiap laporan akan diverifikasi oleh petugas, kemudian ditindaklanjuti sesuai dengan jenis dan tingkat keparahan masalah. Nama petugas yang menangani laporan Anda akan tercantum pada detail proses pengerjaan.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Bagaimana jika saya menemukan bug atau masalah pada sistem?</span>
                <i class="bi bi-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Jika Anda mengalami kendala teknis atau menemukan bug pada sistem SanitaCheck, silakan laporkan melalui:
                    <ul class="mt-2">
                        <li><i class="bi bi-envelope me-1"></i> Email: <a href="mailto:sanitacheck@itsk-soepraoen.ac.id">sanitacheck@itsk-soepraoen.ac.id</a></li>
                        <li><i class="bi bi-geo-alt me-1"></i> Kantor Sanitasi Kampus ITSK Soepraoen</li>
                    </ul>
                    Tim pengembang akan meninjau laporan Anda dan melakukan perbaikan secepat mungkin. Partisipasi Anda sangat berarti untuk pengembangan sistem ke depannya.
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function toggleFaq(btn) {
    var item = btn.closest('.faq-item');
    var isOpen = item.classList.contains('open');

    document.querySelectorAll('.faq-item.open').forEach(function(el) {
        el.classList.remove('open');
    });

    if (!isOpen) {
        item.classList.add('open');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var first = document.querySelector('.faq-item');
    if (first) first.classList.add('open');
});
</script>
<?php include 'components/footer.php'; ?>
