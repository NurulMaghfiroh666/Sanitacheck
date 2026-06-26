@extends('layouts.admin')

@section('title', 'Progres Inspeksi')

@section('content')
<div class="mb-3">
    <div class="d-flex align-items-center gap-2 mb-1">
        <h3 class="fw-bold mb-0" style="color: #1b5e3a;">Progres Inspeksi</h3>
    </div>
    <div class="d-flex align-items-center text-body-secondary small">
        <span>Dashboard</span>
        <span class="mx-2">/</span>
        <span>Jadwal Petugas</span>
        <span class="mx-2">/</span>
        <span>Progres</span>
    </div>
</div>

<div class="card border shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h5 class="card-title fw-bold mb-2" style="color: #333; font-size: 1.1rem;">Detail Jadwal Inspeksi</h5>
                <div class="d-flex gap-3 text-body-secondary small">
                    <div><strong>Petugas:</strong> {{ $jadwal->petugas->nama_petugas ?? '-' }}</div>
                    <div><strong>Fasilitas:</strong> {{ $jadwal->fasilitas->nama_fasilitas ?? '-' }} ({{ $jadwal->fasilitas->lokasi ?? '-' }})</div>
                    <div><strong>Jenis Tugas:</strong> {{ $jadwal->jenis_tugas }}</div>
                </div>
            </div>
            <a href="{{ route('jadwal-petugas.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 6px;">
                &larr; Kembali
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded" style="border-radius: 8px;">
                    <div class="text-body-secondary small">Tanggal Mulai</div>
                    <div class="fw-bold" style="color: #333;">{{ $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-' }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded" style="border-radius: 8px;">
                    <div class="text-body-secondary small">Tanggal Selesai</div>
                    <div class="fw-bold" style="color: #333;">{{ $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-' }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded" style="border-radius: 8px;">
                    <div class="text-body-secondary small">Status</div>
                    <div class="fw-bold">
                        @if($jadwal->status == 'Menunggu')
                        <span class="badge bg-secondary">Menunggu</span>
                        @elseif($jadwal->status == 'Berlangsung')
                        <span class="badge bg-primary">Berlangsung</span>
                        @elseif($jadwal->status == 'Selesai')
                        <span class="badge bg-success">Selesai</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded" style="border-radius: 8px;">
                    <div class="text-body-secondary small">Total Foto</div>
                    <div class="fw-bold" style="color: #333;">{{ $jadwal->photos->count() }} foto</div>
                </div>
            </div>
        </div>

        @if($jadwal->catatan)
        <div class="mb-4">
            <div class="text-body-secondary small mb-1">Catatan</div>
            <div class="p-3 bg-light rounded" style="border-radius: 8px; color: #333;">{{ $jadwal->catatan }}</div>
        </div>
        @endif

        <h6 class="fw-bold mb-3" style="color: #333;">Foto Inspeksi</h6>
        
        @if($jadwal->photos->isEmpty())
        <div class="text-center py-5 bg-light rounded" style="border-radius: 12px;">
            <svg style="width: 48px; height: 48px; color: #6c757d;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M448 80h-83.8c-7.4-28.1-32.9-49.5-63.2-49.5s-55.8 21.4-63.2 49.5H154.2c-7.4-28.1-32.9-49.5-63.2-49.5S35.2 51.9 27.8 80H16C7.2 80 0 87.2 0 96v320c0 8.8 7.2 16 16 16h432c8.8 0 16-7.2 16-16V96c0-8.8-7.2-16-16-16zM301.1 46.5c18.8 0 34.9 13.1 39.1 31.5H262c4.2-18.4 20.3-31.5 39.1-31.5zM90.9 46.5c18.8 0 34.9 13.1 39.1 31.5H51.8c4.2-18.4 20.3-31.5 39.1-31.5zM432 400H80V112h352v288z"/>
            </svg>
            <p class="text-muted mt-2 mb-0">Belum ada foto inspeksi yang diunggah.</p>
        </div>
        @else
        <div class="row">
            @foreach($jadwal->photos as $photo)
            <div class="col-md-4 mb-3">
                <div class="card border shadow-sm" style="border-radius: 12px;">
                    <img src="{{ asset('storage/' . $photo->foto_path) }}" alt="Foto Inspeksi" class="card-img-top" style="height: 200px; object-fit: cover; border-radius: 12px 12px 0 0;">
                    <div class="card-body p-3">
                        <div class="small text-body-secondary mb-1">Diunggah oleh: {{ $photo->petugas->nama_petugas ?? '-' }}</div>
                        <div class="small text-body-secondary">Tanggal: {{ $photo->created_at->format('d/m/Y H:i') }}</div>
                        @if($photo->keterangan)
                        <div class="small mt-2" style="color: #333;">{{ $photo->keterangan }}</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection
