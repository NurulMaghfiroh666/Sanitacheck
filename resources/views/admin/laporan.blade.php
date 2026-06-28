@extends('layouts.admin')

@section('title', 'Rekap & Laporan')

@section('content')
<div class="mb-3">
    <h3 class="fw-bold mb-1" style="color: #1b5e3a;">Rekap & Laporan</h3>
    <p class="text-body-secondary" style="font-size: 0.9rem;">Rekapitulasi hasil inspeksi sanitasi dan kebersihan fasilitas umum</p>
</div>



<!-- KPI Cards -->
<div class="row mb-3">
    @foreach($kpis as $i => $kpi)
    <div class="col-sm-6 col-lg-3 mb-2">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-{{ $kpi[3] }} text-white me-3 d-flex justify-content-center align-items-center shadow" style="border-radius: 12px; width: 48px; height: 48px; min-width: 48px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    @if($i === 5)
                        <svg class="icon" style="width: 24px; height: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 48C141.31 48 48 141.31 48 256s93.31 208 208 208 208-93.31 208-208S370.69 48 256 48m0 368a160 160 0 1 1 160-160 160.182 160.182 0 0 1-160 160"/><path fill="currentColor" d="M256 128a128 128 0 1 0 128 128 128.144 128.144 0 0 0-128-128m0 192a64 64 0 1 1 64-64 64.072 64.072 0 0 1-64 64"/></svg>
                    @else
                        <svg class="icon" style="width: 24px; height: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="{{ $kpi[4] }}"/></svg>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="text-body-secondary fw-semibold mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ $kpi[0] }}</div>
                    <div class="fw-bold text-body mb-1" style="font-size: 1.5rem; line-height: 1.2;">{{ $kpi[1] }}</div>
                    <div class="text-body-secondary" style="font-size: 0.7rem;">{{ $kpi[2] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Charts Row -->
<div class="row mb-3">
    <div class="col-lg-5 mb-3 mb-lg-0">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <h6 class="card-title text-success fw-bold mb-3">Tren Inspeksi per Bulan</h6>
                <div style="position: relative; height: 220px;">
                    <canvas id="trendLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3 mb-lg-0">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <h6 class="card-title text-success fw-bold mb-3">Persentase Kondisi Sanitasi</h6>
                <div style="position: relative; height: 220px;">
                    <canvas id="conditionDoughnut"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <h6 class="card-title text-success fw-bold mb-3">Ringkasan Kondisi</h6>
                @foreach([
                    ['Aman / Baik', $kondisiBaik, $baikP, 'success'],
                    ['Perlu Dibersihkan', $kondisiCukup, $cukupP, 'warning'],
                    ['Perlu Perbaikan / Buruk', $kondisiBuruk, $burukP, 'danger'],
                ] as $status)
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-semibold text-body-secondary">{{ $status[0] }}</span>
                        <span class="text-body-secondary">{{ $status[1] }} ({{ $status[2] }}%)</span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-{{ $status[3] }}" style="width: {{ $status[2] }}%;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row mb-3">
    <div class="col-lg-8 mb-3 mb-lg-0">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <h6 class="card-title text-success fw-bold mb-3">Rekap Sanitasi per Fasilitas</h6>
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle mb-0">
                        <thead style="border-bottom: 1px solid var(--cui-border-color);">
                            <tr>
                                <th class="text-center text-success py-2" style="font-size: 0.75rem;">No</th>
                                <th class="text-success py-2" style="font-size: 0.75rem;">Fasilitas</th>
                                <th class="text-success py-2" style="font-size: 0.75rem;">Lokasi</th>
                                <th class="text-center text-success py-2" style="font-size: 0.75rem;">Total</th>
                                <th class="text-center text-success py-2" style="font-size: 0.75rem;">Baik</th>
                                <th class="text-center text-success py-2" style="font-size: 0.75rem;">Cukup</th>
                                <th class="text-center text-success py-2" style="font-size: 0.75rem;">Buruk</th>
                                <th class="text-center text-success py-2" style="font-size: 0.75rem;">Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapFasilitas as $i => $row)
                            <tr>
                                <td class="text-center text-body-secondary small py-2">{{ $i + 1 }}</td>
                                <td class="small py-2 fw-semibold">{{ $row[0] }}</td>
                                <td class="text-body-secondary small py-2">{{ $row[1] }}</td>
                                <td class="text-center text-body-secondary small py-2">{{ $row[2] }}</td>
                                <td class="text-center small py-2"><span class="text-success fw-semibold">{{ $row[3] }}</span></td>
                                <td class="text-center small py-2"><span class="text-warning fw-semibold">{{ $row[4] }}</span></td>
                                <td class="text-center small py-2"><span class="text-danger fw-semibold">{{ $row[5] }}</span></td>
                                <td class="text-center py-2">
                                    <span class="small fw-bold" style="font-size: 0.8rem;">{{ $row[6] }}</span>
                                    <span class="badge {{ $row[7] === 'warning' ? 'bg-warning text-dark' : 'bg-'.$row[7] }} ms-1" style="font-size: 0.7rem;">{{ $row[8] }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data rekap fasilitas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border shadow-sm mb-3" style="border-radius: 12px;">
            <div class="card-body p-3">
                <h6 class="card-title text-success fw-bold mb-3">Top 5 Fasilitas Bermasalah</h6>
                @forelse($fasilitasBermasalah as $i => $item)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <span class="text-body-secondary small me-2 fw-bold">{{ $i + 1 }}</span>
                        <div>
                            <span class="small fw-semibold">{{ $item['nama_fasilitas'] }}</span>
                            <div class="text-body-secondary" style="font-size: 0.65rem;">{{ $item['lokasi'] }}</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge {{ $item['avg_score'] < 50 ? 'bg-danger' : ($item['avg_score'] < 70 ? 'bg-warning text-dark' : 'bg-info text-dark') }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">Skor: {{ $item['avg_score'] }}</span>
                        <div class="text-body-secondary small" style="font-size: 0.65rem;">{{ $item['problem_count'] }} masalah</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-3 text-muted small">Belum ada data fasilitas bermasalah.</div>
                @endforelse
            </div>
        </div>
        <div class="card border shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-3">
                <h6 class="card-title text-success fw-bold mb-3">Top 5 Petugas Teraktif</h6>
                @forelse($topPetugas as $i => $petugas)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <span class="text-body-secondary small me-2 fw-bold">{{ $i + 1 }}</span>
                        <div>
                            <span class="small fw-semibold">{{ $petugas['nama'] }}</span>
                            <div class="text-body-secondary" style="font-size: 0.65rem;">{{ $petugas['inspeksi_count'] }} inspeksi</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $petugas['total_score'] }} pts</span>
                        <div class="text-body-secondary small" style="font-size: 0.65rem;">Avg: {{ $petugas['avg_score'] }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-3 text-muted small">Belum ada data petugas teraktif.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Laporan Terbaru & Export -->
<div class="row mb-4">
    <div class="col-lg-8 mb-3 mb-lg-0">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <h6 class="card-title text-success fw-bold mb-3">Laporan Terbaru</h6>
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle mb-0">
                        <thead style="border-bottom: 1px solid var(--cui-border-color);">
                            <tr>
                                <th class="text-center text-success py-2" style="font-size: 0.8rem;">No</th>
                                <th class="text-success py-2" style="font-size: 0.8rem;">Fasilitas</th>
                                <th class="text-success py-2" style="font-size: 0.8rem;">Petugas</th>
                                <th class="text-success py-2" style="font-size: 0.8rem;">Tanggal</th>
                                <th class="text-center text-success py-2" style="font-size: 0.8rem;">Skor</th>
                                <th class="text-center text-success py-2" style="font-size: 0.8rem;">Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestInspeksi as $i => $inspeksi)
                            <tr>
                                <td class="text-center text-body-secondary small py-2">{{ $i + 1 }}</td>
                                <td class="small py-2">
                                    <div class="fw-semibold">{{ $inspeksi['fasilitas'] }}</div>
                                    <div class="text-body-secondary" style="font-size: 0.7rem;">{{ $inspeksi['lokasi'] }}</div>
                                </td>
                                <td class="text-body-secondary small py-2">{{ $inspeksi['petugas'] }}</td>
                                <td class="text-body-secondary small py-2">{{ $inspeksi['tanggal'] }}</td>
                                <td class="text-center py-2">
                                    <span class="badge {{ $inspeksi['skor'] >= 80 ? 'bg-success' : ($inspeksi['skor'] >= 60 ? 'bg-warning text-dark' : 'bg-danger') }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $inspeksi['skor'] }}</span>
                                </td>
                                <td class="text-center py-2">
                                    @if(count($inspeksi['photos']) > 0)
                                        <button class="btn btn-sm btn-outline-primary border-0 p-1" onclick="openInspeksiPhotos({{ $i }})" title="Lihat Foto ({{ count($inspeksi['photos']) }})">
                                            <svg style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 112C146.45 112 49.28 182.18 16.37 253.24a16 16 0 0 0 0 13.52C49.28 337.82 146.45 408 256 408s206.72-70.18 239.63-141.24a16 16 0 0 0 0-13.52C462.72 182.18 365.55 112 256 112m0 256a112 112 0 1 1 112-112 112.126 112.126 0 0 1-112 112"/></svg>
                                        </button>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada laporan inspeksi terbaru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <h6 class="card-title text-success fw-bold mb-1">Daftar Keluhan</h6>
                <p class="text-body-secondary small mb-3">Klik pada baris untuk melihat detail keluhan.</p>
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle mb-0">
                        <thead style="border-bottom: 1px solid var(--cui-border-color);">
                            <tr>
                                <th class="text-center text-success py-2" style="font-size: 0.75rem;">No</th>
                                <th class="text-success py-2" style="font-size: 0.75rem;">Fasilitas</th>
                                <th class="text-success py-2" style="font-size: 0.75rem;">Pelapor</th>
                                <th class="text-success py-2" style="font-size: 0.75rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($keluhanList as $i => $keluhan)
                            <tr style="cursor: pointer;" onclick="showKeluhanDetail({{ $i }})">
                                <td class="text-center text-body-secondary small py-2">{{ $i + 1 }}</td>
                                <td class="small py-2" style="max-width: 140px;">
                                    <div class="fw-semibold text-truncate" style="font-size: 0.7rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $keluhan['fasilitas_nama'] }}">{{ $keluhan['fasilitas_nama'] }}</div>
                                    <div class="text-body-secondary text-truncate" style="font-size: 0.65rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $keluhan['lokasi'] }}">{{ $keluhan['lokasi'] }}</div>
                                </td>
                                <td class="text-body-secondary small py-2" style="font-size: 0.7rem;">{{ $keluhan['nama_pelapor'] }}</td>
                                <td class="py-2">
                                    <span class="badge {{ $keluhan['status'] === 'Selesai' ? 'bg-success' : ($keluhan['status'] === 'Proses' ? 'bg-warning text-dark' : 'bg-info text-dark') }} rounded-pill px-2 py-1" style="font-size: 0.65rem;">{{ $keluhan['status'] }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada keluhan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Modal Detail Keluhan -->
<div class="modal fade" id="keluhanDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.18);">
            <div class="modal-header border-bottom pb-2" style="background: linear-gradient(135deg, #1b5e3a 0%, #2d8f5e 100%); border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold text-white d-flex align-items-center">
                    <svg class="me-2" style="width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                    Detail Keluhan
                </h6>
                <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-2">
                <div id="keluhanDetailContent">
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

<!-- Modal Foto Inspeksi -->
<div class="modal fade" id="inspeksiPhotosModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.18);">
            <div class="modal-header border-bottom pb-2" style="background: linear-gradient(135deg, #1b5e3a 0%, #2d8f5e 100%); border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold text-white d-flex align-items-center">
                    <svg class="me-2" style="width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 112C146.45 112 49.28 182.18 16.37 253.24a16 16 0 0 0 0 13.52C49.28 337.82 146.45 408 256 408s206.72-70.18 239.63-141.24a16 16 0 0 0 0-13.52C462.72 182.18 365.55 112 256 112m0 256a112 112 0 1 1 112-112 112.126 112.126 0 0 1-112 112"/></svg>
                    Foto Inspeksi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-2">
                <div id="inspeksiPhotosContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status"></div>
                        <p class="text-muted mt-2">Memuat foto...</p>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const latestInspeksiData = @json($latestInspeksi);
const keluhanListData = @json($keluhanList);

function openInspeksiPhotos(index) {
    const modal = new coreui.Modal(document.getElementById('inspeksiPhotosModal'));
    const content = document.getElementById('inspeksiPhotosContent');
    const inspeksi = latestInspeksiData[index];
    
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-success" role="status"></div>
            <p class="text-muted mt-2">Memuat foto...</p>
        </div>
    `;
    
    modal.show();
    
    setTimeout(() => {
        let photosHtml = '';
        if (inspeksi.photos && inspeksi.photos.length > 0) {
            photosHtml = `
                <div class="mb-3">
                    <h6 class="fw-bold mb-2 text-body" style="font-size: 0.9rem;">${inspeksi.fasilitas}</h6>
                    <div class="text-body-secondary mb-3" style="font-size: 0.8rem;">
                        Petugas: ${inspeksi.petugas} | Tanggal: ${inspeksi.tanggal}
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        ${inspeksi.photos.map(photo => `
                            <div class="position-relative" style="width: 120px; height: 120px; cursor: pointer;" onclick="openPreview('${window.location.origin}/storage/${photo.foto_path}')">
                                <img src="${window.location.origin}/storage/${photo.foto_path}" alt="Foto" class="img-fluid rounded shadow-sm" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        } else {
            photosHtml = `
                <div class="text-center py-3 bg-light rounded" style="border-radius: 8px;">
                    <i class="bi bi-images text-secondary" style="font-size: 1.5rem;"></i>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.75rem;">Belum ada foto.</p>
                </div>
            `;
        }
        
        content.innerHTML = photosHtml;
    }, 300);
}

function openPreview(src) {
    document.getElementById('previewImage').src = src;
    document.getElementById('downloadImageBtn').href = src;
    var modal = new coreui.Modal(document.getElementById('modalPreviewFoto'));
    modal.show();
}

function showKeluhanDetail(index) {
    const modal = new coreui.Modal(document.getElementById('keluhanDetailModal'));
    const content = document.getElementById('keluhanDetailContent');
    const keluhan = keluhanListData[index];
    
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-success" role="status"></div>
            <p class="text-muted mt-2">Memuat data...</p>
        </div>
    `;
    
    modal.show();
    
    setTimeout(() => {
        const statusColors = {
            'Menunggu': { bg: '#e8f0fe', text: '#1a73e8' },
            'Proses': { bg: '#fff3cd', text: '#856404' },
            'Selesai': { bg: '#d4edda', text: '#155724' }
        };
        const statusColor = statusColors[keluhan.status] || { bg: '#e2e3e5', text: '#383d41' };
        
        let photoHtml = '';
        if (keluhan.foto) {
            photoHtml = `
                <div class="mb-3">
                    <h6 class="fw-bold mb-2 text-body" style="font-size: 0.8rem;">Foto Keluhan</h6>
                    <div class="text-center">
                        <img src="${window.location.origin}/${keluhan.foto}" alt="Foto Keluhan" class="img-fluid rounded shadow-sm" style="max-height: 200px; cursor: pointer;" onclick="openPreview('${window.location.origin}/${keluhan.foto}')">
                    </div>
                </div>
            `;
        } else {
            photoHtml = `
                <div class="mb-3">
                    <h6 class="fw-bold mb-2 text-body" style="font-size: 0.8rem;">Foto Keluhan</h6>
                    <div class="text-center py-3 bg-light rounded" style="border-radius: 8px;">
                        <i class="bi bi-images text-secondary" style="font-size: 1.5rem;"></i>
                        <p class="text-muted mt-1 mb-0" style="font-size: 0.75rem;">Tidak ada foto.</p>
                    </div>
                </div>
            `;
        }
        
        content.innerHTML = `
            <div class="row g-3">
                <div class="col-12">
                    <div class="text-body-secondary small text-uppercase">ID Keluhan</div>
                    <div class="fw-bold text-body" style="font-size: 0.85rem;">${keluhan.id_keluhan}</div>
                </div>
                <div class="col-12">
                    <div class="text-body-secondary small text-uppercase">Fasilitas</div>
                    <div class="fw-bold text-body" style="font-size: 0.85rem;">${keluhan.fasilitas_nama}</div>
                    <div class="text-muted small">${keluhan.lokasi}</div>
                </div>
                <div class="col-6">
                    <div class="text-body-secondary small text-uppercase">Pelapor</div>
                    <div class="fw-semibold text-body" style="font-size: 0.85rem;">${keluhan.nama_pelapor}</div>
                </div>
                <div class="col-6">
                    <div class="text-body-secondary small text-uppercase">Status</div>
                    <div>
                        <span class="badge rounded-pill px-2.5 py-1" style="font-size: 0.7rem; background-color: ${statusColor.bg}; color: ${statusColor.text};">
                            ${keluhan.status}
                        </span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="text-body-secondary small text-uppercase">Tanggal Laporan</div>
                    <div class="fw-semibold text-body" style="font-size: 0.85rem;">${keluhan.tanggal}</div>
                </div>
                <div class="col-12">
                    <div class="text-body-secondary small text-uppercase">Catatan Keluhan</div>
                    <div class="p-2 bg-light rounded small text-body">${keluhan.catatan}</div>
                </div>
                ${keluhan.resolution ? `
                <div class="col-12">
                    <div class="text-body-secondary small text-uppercase">Resolution</div>
                    <div class="p-2 bg-light rounded small text-body">${keluhan.resolution}</div>
                </div>
                ` : ''}
            </div>
            ${photoHtml}
        `;
    }, 300);
}

document.addEventListener("DOMContentLoaded", function() {
    function getThemeColors() {
        const isDarkMode = document.documentElement.getAttribute('data-coreui-theme') === 'dark';
        return {
            // Perbaikan warna text chart agar fleksibel saat dark/light mode
            textColor: isDarkMode ? 'rgba(255, 255, 255, 0.87)' : '#212529',
            gridColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)',
        };
    }

    let colors = getThemeColors();

    // Trend Line Chart
    let trendChart = new Chart(document.getElementById('trendLineChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_reverse($trendLabels)) !!},
            datasets: [
                { label: 'Total Inspeksi', data: {!! json_encode(array_reverse($trendData)) !!}, borderColor: '#198754', backgroundColor: 'rgba(25, 135, 84, 0.1)', borderWidth: 2, fill: true, tension: 0.3, pointRadius: 3 }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, color: colors.textColor, font: { size: 11 } } } },
            scales: {
                y: { beginAtZero: true, ticks: { color: colors.textColor }, grid: { color: colors.gridColor } },
                x: { ticks: { color: colors.textColor }, grid: { display: false } }
            }
        }
    });

    // Condition Doughnut
    let conditionChart = new Chart(document.getElementById('conditionDoughnut').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Baik ('+{{ $baikP }}+'%)', 'Cukup ('+{{ $cukupP }}+'%)', 'Buruk ('+{{ $burukP }}+'%)'],
            datasets: [{ data: [{{ $kondisiBaik }}, {{ $kondisiCukup }}, {{ $kondisiBuruk }}], backgroundColor: ['#28a745', '#ffc107', '#dc3545'], borderWidth: 0 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '70%',
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, color: colors.textColor, font: { size: 11 } } } }
        },
        plugins: [{
            id: 'centerText',
            beforeDraw: function(chart) {
                const ctx = chart.ctx;
                ctx.restore();
                ctx.font = "bold 2em sans-serif";
                ctx.textBaseline = "middle";
                ctx.fillStyle = colors.textColor;
                const text = "{{ $kondisiBaik + $kondisiCukup + $kondisiBuruk }}", textX = Math.round((chart.chartArea.left + chart.chartArea.right - ctx.measureText(text).width) / 2), textY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                ctx.fillText(text, textX, textY - 10); // sedikit naik agar teks "Total" pas di bawahnya
                ctx.font = "0.8em sans-serif";
                ctx.fillText("Total", textX + 2, textY + 15);
                ctx.save();
            }
        }]
    });

    // Observer untuk mendeteksi perubahan tema tanpa refresh halaman
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'data-coreui-theme') {
                colors = getThemeColors();
                
                // Update Trend Line Chart
                trendChart.options.plugins.legend.labels.color = colors.textColor;
                trendChart.options.scales.y.ticks.color = colors.textColor;
                trendChart.options.scales.y.grid.color = colors.gridColor;
                trendChart.options.scales.x.ticks.color = colors.textColor;
                trendChart.update();

                // Update Doughnut Chart
                conditionChart.options.plugins.legend.labels.color = colors.textColor;
                conditionChart.update();
            }
        });
    });

    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-coreui-theme'] });
});
</script>
@endpush