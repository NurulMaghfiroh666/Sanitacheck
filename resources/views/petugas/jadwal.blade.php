@extends('layouts.petugas')

@section('title', 'Jadwal Inspeksi | SanitaCheck')

@section('content')
<div class="mb-3">
    <h5 class="fw-bold mb-1"><i class="bi bi-calendar2-check text-success me-2"></i>Jadwal Inspeksi</h5>
    <p class="text-body-secondary mb-0 d-md-none" style="font-size: 0.8rem;">Daftar jadwal penugasan inspeksi Anda</p>
</div>

@if($jadwalDitugaskan->isEmpty())
<div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
    <div class="card-body">
        <i class="bi bi-calendar-x text-secondary" style="font-size: 3rem;"></i>
        <p class="text-secondary mt-2 mb-0">Belum ada jadwal inspeksi saat ini.</p>
    </div>
</div>
@else

<div class="d-block d-md-none">
    @foreach($jadwalDitugaskan as $index => $jadwal)
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; border-left: 4px solid 
        @if($jadwal->status == 'Menunggu') #6c757d 
        @elseif($jadwal->status == 'Berlangsung') #0d6efd 
        @elseif($jadwal->status == 'Selesai') #198754 
        @else #dc3545 @endif !important;">
        
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                <div>
                    <span class="text-secondary small fw-bold">#{{ $index + 1 }}</span>
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $jadwal->jenis_tugas }}</div>
                </div>
                <div>
                    @if($jadwal->status == 'Menunggu')
                        <span class="badge bg-secondary" style="font-size: 0.65rem;">Menunggu</span>
                    @elseif($jadwal->status == 'Berlangsung')
                        <span class="badge bg-primary" style="font-size: 0.65rem;">Berlangsung</span>
                    @elseif($jadwal->status == 'Selesai')
                        <span class="badge bg-success" style="font-size: 0.65rem;">Selesai</span>
                    @elseif($jadwal->status == 'Dibatalkan')
                        <span class="badge bg-danger" style="font-size: 0.65rem;">Dibatalkan</span>
                    @endif
                </div>
            </div>

            <div class="mb-3" style="font-size: 0.8rem;">
                <div class="text-dark fw-semibold mb-1">
                    <i class="bi bi-building me-1 text-secondary"></i>{{ $jadwal->fasilitas->nama_fasilitas ?? '-' }}
                </div>
                <div class="text-secondary mb-2" style="padding-left: 15px;">
                    {{ $jadwal->fasilitas->lokasi ?? '-' }}
                </div>
                <div class="text-secondary">
                    <i class="bi bi-calendar-event me-1 text-secondary"></i> 
                    {{ $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-' }} 
                    <span class="text-muted">s/d</span> 
                    {{ $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-' }}
                </div>
            </div>

            <div class="p-2 rounded-3 bg-light mb-3 d-flex justify-content-between align-items-center" style="font-size: 0.75rem;">
                <div><i class="bi bi-images me-1 text-success"></i><strong>{{ $jadwal->photos->count() }}</strong> Foto</div>
                @if($jadwal->catatan)
                    <div class="text-secondary text-truncate ms-2" style="max-width: 180px;" title="{{ $jadwal->catatan }}">
                        <i class="bi bi-sticky me-1"></i>{{ $jadwal->catatan }}
                    </div>
                @endif
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('petugas.upload-foto', $jadwal->id) }}" class="btn btn-success btn-sm fw-semibold flex-grow-1 py-2" style="border-radius: 8px; font-size: 0.8rem;">
                    <i class="bi bi-camera me-1"></i> Upload
                </a>
                @if($jadwal->status != 'Selesai')
                <a href="{{ route('petugas.inspeksi.create', $jadwal->fasilitas_id) }}" class="btn btn-outline-primary btn-sm fw-semibold flex-grow-1 py-2" style="border-radius: 8px; font-size: 0.8rem;">
                    <i class="bi bi-pencil-square me-1"></i> Isi Form
                </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm d-none d-md-block" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0">
                <thead style="border-bottom: 1px solid #dee2e6; background-color: #f8f9fa;">
                    <tr>
                        <th class="py-3 px-3" style="font-size: 0.8rem; font-weight: 600; width: 5%">No</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Jenis Tugas</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600; width: 25%">Fasilitas</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600; width: 20%">Tanggal</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Status</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600; width: 15%">Inspeksi</th>
                        <th class="py-3 text-center px-3" style="font-size: 0.8rem; font-weight: 600; width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwalDitugaskan as $index => $jadwal)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td class="py-3 px-3 text-secondary small">{{ $index + 1 }}</td>
                        <td class="py-3">
                            <div class="fw-semibold text-dark" style="font-size: 0.85rem;">{{ $jadwal->jenis_tugas }}</div>
                        </td>
                        <td class="py-3">
                            <div>
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;">{{ $jadwal->fasilitas->nama_fasilitas ?? '-' }}</div>
                                <div class="text-secondary small">{{ $jadwal->fasilitas->lokasi ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="small text-dark">
                                <div><i class="bi bi-calendar2-event me-1 text-muted"></i>{{ $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-' }}</div>
                                <div class="text-secondary ps-3" style="font-size: 0.75rem;">s/d {{ $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-' }}</div>
                            </div>
                        </td>
                        <td class="py-3">
                            @if($jadwal->status == 'Menunggu')
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">Menunggu</span>
                            @elseif($jadwal->status == 'Berlangsung')
                                <span class="badge bg-primary" style="font-size: 0.7rem;">Berlangsung</span>
                            @elseif($jadwal->status == 'Selesai')
                                <span class="badge bg-success" style="font-size: 0.7rem;">Selesai</span>
                            @elseif($jadwal->status == 'Dibatalkan')
                                <span class="badge bg-danger" style="font-size: 0.7rem;">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="small">
                                <div class="text-dark"><i class="bi bi-images me-1 text-muted"></i>{{ $jadwal->photos->count() }} foto</div>
                                @if($jadwal->catatan)
                                    <div class="text-secondary text-truncate small" style="max-width: 140px;" title="{{ $jadwal->catatan }}">{{ $jadwal->catatan }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 text-center px-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('petugas.upload-foto', $jadwal->id) }}" class="btn btn-success btn-sm fw-semibold" style="border-radius: 6px; font-size: 0.75rem;">
                                    <i class="bi bi-camera me-1"></i> Upload
                                </a>
                                @if($jadwal->status != 'Selesai')
                                <a href="{{ route('petugas.inspeksi.create', $jadwal->fasilitas_id) }}" class="btn btn-outline-primary btn-sm fw-semibold" style="border-radius: 6px; font-size: 0.75rem;">
                                    <i class="bi bi-pencil-square me-1"></i> Isi
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection