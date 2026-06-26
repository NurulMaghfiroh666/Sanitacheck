@extends('layouts.petugas')

@section('title', 'Jadwal Inspeksi | SanitaCheck')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-calendar2-check text-success me-2"></i>Jadwal Inspeksi</h5>

@if($jadwalDitugaskan->isEmpty())
<div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
    <div class="card-body">
        <i class="bi bi-calendar-x text-secondary" style="font-size: 3rem;"></i>
        <p class="text-secondary mt-2 mb-0">Belum ada jadwal inspeksi saat ini.</p>
    </div>
</div>
@else
<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0">
                <thead style="border-bottom: 1px solid #dee2e6; background-color: #f8f9fa;">
                    <tr>
                        <th class="py-3 px-3" style="font-size: 0.8rem; font-weight: 600;">No</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Jenis Tugas</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Fasilitas</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Tanggal</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Status</th>
                        <th class="py-3" style="font-size: 0.8rem; font-weight: 600;">Inspeksi</th>
                        <th class="py-3 text-center px-3" style="font-size: 0.8rem; font-weight: 600;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwalDitugaskan as $index => $jadwal)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td class="py-3 px-3">{{ $index + 1 }}</td>
                        <td class="py-3">
                            <div class="fw-semibold text-dark">{{ $jadwal->jenis_tugas }}</div>
                        </td>
                        <td class="py-3">
                            <div>
                                <div class="fw-semibold text-dark">{{ $jadwal->fasilitas->nama_fasilitas ?? '-' }}</div>
                                <div class="text-secondary small">{{ $jadwal->fasilitas->lokasi ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="small">
                                <div>{{ $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-' }}</div>
                                <div class="text-secondary">s/d {{ $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-' }}</div>
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
                                <div><i class="bi bi-images me-1"></i>{{ $jadwal->photos->count() }} foto</div>
                                @if($jadwal->catatan)
                                <div class="text-secondary text-truncate" style="max-width: 150px;">{{ $jadwal->catatan }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 text-center px-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('petugas.upload-foto', $jadwal->id) }}" class="btn btn-success btn-sm fw-semibold" style="border-radius: 6px;">
                                    <i class="bi bi-camera me-1"></i> Upload
                                </a>
                                @if($jadwal->status != 'Selesai')
                                <a href="{{ route('petugas.inspeksi.create', $jadwal->fasilitas_id) }}" class="btn btn-outline-primary btn-sm fw-semibold" style="border-radius: 6px;">
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
