@extends('layouts.admin')

@section('title', 'Riwayat Jadwal Rutin & Tugas Inspeksi')

@section('content')
<div class="mb-3">
    <h3 class="fw-bold mb-1" style="color: #1b5e3a;"><i class="bi bi-clock-history me-2"></i>Riwayat Jadwal Rutin & Tugas Inspeksi</h3>
    <p class="text-body-secondary" style="font-size: 0.9rem;">Arsip seluruh jadwal rutin pembersihan dan tugas inspeksi fasilitas</p>
</div>

<!-- KPI Cards (Compact Design) -->
<div class="row g-2 mb-3">
    <div class="col-sm-6">
        <div class="card border-0 shadow-sm" style="border-radius: 10px; background: #fff;">
            <div class="card-body p-2.5 d-flex align-items-center">
                <div class="me-2 d-flex justify-content-center align-items-center" style="border-radius: 8px; width: 36px; height: 36px; min-width: 36px; background-color: #e8f0fe; color: #1a73e8;">
                    <i class="bi bi-calendar-event fs-5"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">JADWAL RUTIN</div>
                    <div class="fw-bold text-primary" style="font-size: 1.2rem; line-height: 1.1;">{{ $totalJadwalRutin }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card border-0 shadow-sm" style="border-radius: 10px; background: #fff;">
            <div class="card-body p-2.5 d-flex align-items-center">
                <div class="me-2 d-flex justify-content-center align-items-center" style="border-radius: 8px; width: 36px; height: 36px; min-width: 36px; background-color: #fce4ec; color: #c2185b;">
                    <i class="bi bi-clipboard-data fs-5"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">TOTAL TUGAS</div>
                    <div class="fw-bold text-danger" style="font-size: 1.2rem; line-height: 1.1;">{{ $totalTugasInspeksi }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Tabs (Compact Accent) -->
<ul class="nav nav-tabs mb-2" id="myTab" role="tablist" style="font-size: 0.85rem;">
  <li class="nav-item" role="presentation">
    <button class="nav-link active text-success fw-bold py-1.5 px-3" id="jadwal-tab" data-coreui-toggle="tab" data-coreui-target="#jadwal" type="button" role="tab" aria-controls="jadwal" aria-selected="true">Jadwal Rutin</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link text-success fw-bold py-1.5 px-3" id="tugas-tab" data-coreui-toggle="tab" data-coreui-target="#tugas" type="button" role="tab" aria-controls="tugas" aria-selected="false">Tugas Inspeksi</button>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
  <!-- Tab Jadwal Rutin -->
  <div class="tab-pane fade show active" id="jadwal" role="tabpanel" aria-labelledby="jadwal-tab">
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; background: #fff;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="font-size: 0.825rem;">
                    <thead style="background-color: #f4faf6;">
                        <tr>
                            <th class="text-center py-2 px-3" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">No</th>
                            <th class="py-2" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Petugas</th>
                            <th class="py-2" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Fasilitas</th>
                            <th class="py-2" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Mulai</th>
                            <th class="py-2" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Selesai</th>
                            <th class="text-center py-2 pe-3" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalRutin as $index => $item)
                        <tr style="border-bottom: 1px solid #f1f3f2; cursor: pointer;" onclick="showJadwalDetail({{ $item->id }})">
                            <td class="text-center text-muted py-2 px-3">{{ $jadwalRutin->firstItem() + $index }}</td>
                            <td class="py-2 fw-bold text-primary">{{ $item->petugas->nama_petugas ?? '-' }}</td>
                            <td class="text-secondary py-2">{{ $item->fasilitas->nama_fasilitas ?? '-' }}</td>
                            <td class="text-muted py-2" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                            <td class="text-muted py-2" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</td>
                            <td class="text-center py-2 pe-3">
                                @if($item->status == 'Selesai')
                                    <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: #d4edda; color: #155724;">Selesai</span>
                                @elseif($item->status == 'Berlangsung')
                                    <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: #e8f0fe; color: #1a73e8;">Berlangsung</span>
                                @elseif($item->status == 'Dibatalkan')
                                    <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: #f8d7da; color: #721c24;">Dibatalkan</span>
                                @else
                                    <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: #fff3cd; color: #856404;">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">Belum ada jadwal rutin.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Inner Wrapper -->
            <div class="d-flex justify-content-between align-items-center p-2.5" style="border-top: 1px solid #f1f3f2;">
                <div class="text-muted" style="font-size: 0.725rem;">{{ $jadwalRutin->firstItem() ?? 0 }}-{{ $jadwalRutin->lastItem() ?? 0 }} dari {{ $jadwalRutin->total() }} data</div>
                <div class="mb-0 custom-pagination-sm" style="transform: scale(0.9); transform-origin: right center;">
                    {{ $jadwalRutin->appends(['tugas_page' => request('tugas_page')])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
  </div>

  <!-- Tab Tugas Inspeksi -->
  <div class="tab-pane fade" id="tugas" role="tabpanel" aria-labelledby="tugas-tab">
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; background: #fff;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="font-size: 0.825rem;">
                    <thead style="background-color: #f4faf6;">
                        <tr>
                            <th class="text-center py-2 px-3" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">No</th>
                            <th class="py-2" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Judul Tugas</th>
                            <th class="py-2" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Fasilitas</th>
                            <th class="py-2" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Tanggal</th>
                            <th class="text-center py-2" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Kuota</th>
                            <th class="text-center py-2 pe-3" style="color: #1a5c38; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tugasInspeksi as $index => $item)
                        <tr style="border-bottom: 1px solid #f1f3f2; cursor: pointer;" onclick="showTugasDetail({{ $item->id }})">
                            <td class="text-center text-muted py-2 px-3">{{ $tugasInspeksi->firstItem() + $index }}</td>
                            <td class="py-2 fw-bold text-primary">{{ $item->judul }}</td>
                            <td class="text-secondary py-2">{{ $item->fasilitas->nama_fasilitas ?? '-' }}</td>
                            <td class="text-muted py-2" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($item->tanggal_tugas)->format('d M Y H:i') }}</td>
                            <td class="text-center py-2">{{ $item->petugas_terdaftar }}/{{ $item->kuota_tim }}</td>
                            <td class="text-center py-2 pe-3">
                                @if($item->status == 'selesai')
                                    <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: #d4edda; color: #155724;">Selesai</span>
                                @elseif($item->status == 'penuh')
                                    <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: #fff3cd; color: #856404;">Penuh</span>
                                @elseif($item->status == 'tersedia')
                                    <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: #e8f0fe; color: #1a73e8;">Tersedia</span>
                                @elseif($item->status == 'dibatalkan')
                                    <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: #f8d7da; color: #721c24;">Dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">Belum ada tugas inspeksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Inner Wrapper -->
            <div class="d-flex justify-content-between align-items-center p-2.5" style="border-top: 1px solid #f1f3f2;">
                <div class="text-muted" style="font-size: 0.725rem;">{{ $tugasInspeksi->firstItem() ?? 0 }}-{{ $tugasInspeksi->lastItem() ?? 0 }} dari {{ $tugasInspeksi->total() }} data</div>
                <div class="mb-0 custom-pagination-sm" style="transform: scale(0.9); transform-origin: right center;">
                    {{ $tugasInspeksi->appends(['jadwal_page' => request('jadwal_page')])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal Detail Jadwal Rutin -->
<div class="modal fade" id="jadwalDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.18);">
            <div class="modal-header border-bottom pb-2" style="background: linear-gradient(135deg, #1b5e3a 0%, #2d8f5e 100%); border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold text-white d-flex align-items-center">
                    <svg class="me-2" style="width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M480 128a64 64 0 0 0-64-64h-16V48h-32v16H144V48h-32v16H96a64 64 0 0 0-64 64v12a4 4 0 0 0 4 4h440a4 4 0 0 0 4-4zm0 36H32v220a64 64 0 0 0 64 64h320a64 64 0 0 0 64-64zm-212 196H176v-32h92zm64-80H176v-32h156z"/></svg>
                    Detail Jadwal Rutin
                </h6>
                <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-2">
                <div id="jadwalDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status"></div>
                        <p class="text-muted mt-2">Memuat data...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-3">
                <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-semibold" data-coreui-dismiss="modal" style="border-radius: 8px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Tugas Inspeksi -->
<div class="modal fade" id="tugasDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.18);">
            <div class="modal-header border-bottom pb-2" style="background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%); border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold text-white d-flex align-items-center">
                    <svg class="me-2" style="width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                    Detail Tugas Inspeksi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-2">
                <div id="tugasDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-danger" role="status"></div>
                        <p class="text-muted mt-2">Memuat data...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-3">
                <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-semibold" data-coreui-dismiss="modal" style="border-radius: 8px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="modalPreviewFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white shadow-none" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="previewImage" src="" class="img-fluid rounded shadow" style="max-height: 80vh;" alt="Preview Foto">
                <div class="mt-3">
                    <a id="downloadImageBtn" href="" download class="btn btn-primary" style="border-radius: 8px; font-weight: 600;">
                        <i class="bi bi-download me-1"></i> Download Foto
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Style tambahan khusus untuk modifikasi pagination Bootstrap agar ringkas */
.custom-pagination-sm .pagination { margin-bottom: 0; padding: 0; }
.custom-pagination-sm .page-link { padding: 0.25rem 0.5rem; font-size: 0.75rem; }

/* Table row hover effect */
tbody tr:hover {
    background-color: #f8f9fa;
}
</style>

@push('scripts')
<script>
function openPreview(src) {
    document.getElementById('previewImage').src = src;
    document.getElementById('downloadImageBtn').href = src;
    var modal = new coreui.Modal(document.getElementById('modalPreviewFoto'));
    modal.show();
}

function showJadwalDetail(id) {
    const modal = new coreui.Modal(document.getElementById('jadwalDetailModal'));
    const content = document.getElementById('jadwalDetailContent');
    
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-success" role="status"></div>
            <p class="text-muted mt-2">Memuat data...</p>
        </div>
    `;
    
    modal.show();
    
    fetch(`/jadwal-petugas/${id}`)
        .then(response => response.json())
        .then(data => {
            const statusColors = {
                'Menunggu': { bg: '#fff3cd', text: '#856404' },
                'Berlangsung': { bg: '#e8f0fe', text: '#1a73e8' },
                'Selesai': { bg: '#d4edda', text: '#155724' },
                'Dibatalkan': { bg: '#f8d7da', text: '#721c24' }
            };
            const statusColor = statusColors[data.status] || { bg: '#e2e3e5', text: '#383d41' };
            
            let photosHtml = '';
            if (data.photos && data.photos.length > 0) {
                photosHtml = `
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2 text-body" style="font-size: 0.8rem;">Bukti Foto Inspeksi</h6>
                        <div class="d-flex flex-wrap gap-2">
                            ${data.photos.map(photo => `
                                <div class="position-relative" style="width: 80px; height: 80px; cursor: pointer;" onclick="openPreview('${window.location.origin}/storage/${photo.foto_path}')">
                                    <img src="${window.location.origin}/storage/${photo.foto_path}" alt="Foto" class="img-fluid rounded shadow-sm" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                photosHtml = `
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2 text-body" style="font-size: 0.8rem;">Bukti Foto Inspeksi</h6>
                        <div class="text-center py-3 bg-light rounded" style="border-radius: 8px;">
                            <i class="bi bi-images text-secondary" style="font-size: 1.5rem;"></i>
                            <p class="text-muted mt-1 mb-0" style="font-size: 0.75rem;">Belum ada foto.</p>
                        </div>
                    </div>
                `;
            }
            
            content.innerHTML = `
                <div class="row g-3">
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Petugas</div>
                        <div class="fw-bold text-body" style="font-size: 0.85rem;">${data.petugas?.nama_petugas || '-'}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Jenis Tugas</div>
                        <div class="fw-bold text-body" style="font-size: 0.85rem;">${data.jenis_tugas}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Fasilitas</div>
                        <div class="fw-bold text-body" style="font-size: 0.85rem;">${data.fasilitas?.nama_fasilitas || '-'}</div>
                        <div class="text-muted small">${data.fasilitas?.lokasi || '-'}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-body-secondary small text-uppercase">Tanggal Mulai</div>
                        <div class="fw-semibold text-body" style="font-size: 0.85rem;">${new Date(data.tanggal_mulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-body-secondary small text-uppercase">Tanggal Selesai</div>
                        <div class="fw-semibold text-body" style="font-size: 0.85rem;">${new Date(data.tanggal_selesai).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-body-secondary small text-uppercase">Status</div>
                        <div>
                            <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: ${statusColor.bg}; color: ${statusColor.text};">
                                ${data.status}
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-body-secondary small text-uppercase">Total Foto</div>
                        <div class="fw-semibold text-body" style="font-size: 0.85rem;">${data.photos?.length || 0} foto</div>
                    </div>
                    ${data.catatan ? `
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Catatan</div>
                        <div class="p-2 bg-light rounded small text-body">${data.catatan}</div>
                    </div>
                    ` : ''}
                </div>
                ${photosHtml}
            `;
        })
        .catch(error => {
            content.innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat data: ${error.message}
                </div>
            `;
        });
}

function showTugasDetail(id) {
    const modal = new coreui.Modal(document.getElementById('tugasDetailModal'));
    const content = document.getElementById('tugasDetailContent');
    
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-danger" role="status"></div>
            <p class="text-muted mt-2">Memuat data...</p>
        </div>
    `;
    
    modal.show();
    
    // Fetch tugas detail - you may need to create an endpoint for this
    // For now, we'll show basic info
    fetch(`/tugas-inspeksi/${id}`)
        .then(response => response.json())
        .then(data => {
            let hasilLaporanHtml = '';
            if (data.status === 'selesai') {
                hasilLaporanHtml = `
                <div class="col-12 mt-3 pt-3 border-top">
                    <div class="text-body-secondary small text-uppercase fw-bold mb-2 text-success">Hasil Laporan Inspeksi</div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Kondisi Kebersihan</div>
                            <div class="fw-medium">${data.kondisi_kebersihan || '-'}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Bau Tidak Sedap</div>
                            <div class="fw-medium">${data.bau_tidak_sedap !== null ? (data.bau_tidak_sedap ? 'Ya' : 'Tidak') : '-'}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Ketersediaan Air</div>
                            <div class="fw-medium">${data.ketersediaan_air !== null ? (data.ketersediaan_air ? 'Ada' : 'Tidak Ada') : '-'}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Ketersediaan Sabun</div>
                            <div class="fw-medium">${data.ketersediaan_sabun !== null ? (data.ketersediaan_sabun ? 'Ada' : 'Tidak Ada') : '-'}</div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="text-muted" style="font-size: 0.7rem;">Catatan Petugas</div>
                            <div class="bg-light p-2 rounded small">${data.laporan_catatan || '-'}</div>
                        </div>
                        ${data.laporan_foto ? `
                        <div class="col-12 mt-2">
                            <div class="text-muted mb-1" style="font-size: 0.7rem;">Foto Bukti</div>
                            <img src="${window.location.origin}/${data.laporan_foto}" class="img-thumbnail shadow-sm" style="max-height: 120px; cursor: pointer; border-radius: 8px;" onclick="openPreview('${window.location.origin}/${data.laporan_foto}')">
                        </div>` : ''}
                    </div>
                </div>
                `;
            }

            content.innerHTML = `
                <div class="row g-3">
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Judul Tugas</div>
                        <div class="fw-bold fs-6">${data.judul}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Fasilitas & Lokasi</div>
                        <div class="fw-bold">${data.fasilitas?.nama_fasilitas || '-'}</div>
                        <div class="text-muted small">${data.fasilitas?.lokasi || '-'}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-body-secondary small text-uppercase">Tanggal Tugas</div>
                        <div class="fw-semibold">${new Date(data.tanggal_tugas).toLocaleString('id-ID')}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-body-secondary small text-uppercase">Status</div>
                        <div>
                            <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: ${getStatusColor(data.status)}; color: ${getStatusTextColor(data.status)};">
                                ${getStatusLabel(data.status)}
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Deskripsi</div>
                        <div class="p-2 bg-light rounded small">${data.deskripsi || 'Tidak ada deskripsi'}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase mb-1">Tim Petugas (${data.petugas_terdaftar}/${data.kuota_tim})</div>
                        <ul class="list-unstyled mb-0">
                            ${data.users && data.users.length > 0 ? 
                                data.users.sort((a,b) => a.id === data.ketua_id ? -1 : 1).map(user => `
                                    <li class="small text-secondary mb-1">
                                        <i class="bi bi-person-fill me-1"></i>${user.petugas?.nama_petugas || user.name}
                                        ${data.ketua_id === user.id ? '<span class="badge bg-success ms-1" style="font-size: 0.6rem;">Ketua</span>' : ''}
                                    </li>
                                `).join('') 
                                : '<li class="small text-muted fst-italic">Belum ada petugas bergabung</li>'
                            }
                        </ul>
                    </div>
                    ${hasilLaporanHtml}
                </div>
            `;
        })
        .catch(error => {
            content.innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat data: ${error.message}
                </div>
            `;
        });
}

function getStatusLabel(status) {
    const labels = {
        'tersedia': 'Tersedia',
        'penuh': 'Penuh',
        'selesai': 'Selesai',
        'dibatalkan': 'Dibatalkan'
    };
    return labels[status] || status;
}

function getStatusColor(status) {
    const colors = {
        'tersedia': '#e8f0fe',
        'penuh': '#fff3cd',
        'selesai': '#d4edda',
        'dibatalkan': '#f8d7da'
    };
    return colors[status] || '#e2e3e5';
}

function getStatusTextColor(status) {
    const colors = {
        'tersedia': '#1a73e8',
        'penuh': '#856404',
        'selesai': '#155724',
        'dibatalkan': '#721c24'
    };
    return colors[status] || '#380f3d41';
}
</script>
@endpush
@endsection