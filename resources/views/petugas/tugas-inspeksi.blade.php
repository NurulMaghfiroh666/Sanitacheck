@extends('layouts.petugas')

@section('title', 'Tugas Inspeksi | SanitaCheck')

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
    <div>
        <h4 class="fw-bold mb-0" style="color: #1b5e3a;">Tugas Inspeksi</h4>
        <p class="text-body-secondary mb-0 small">Ambil tugas inspeksi yang tersedia</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
    {{ session('success') }}
    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
    {{ session('error') }}
    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Tugas Saya (Joined) -->
@if($tugasSaya->isNotEmpty())
<div class="card border-0 shadow-sm mb-3" style="border-radius: 15px;">
    <div class="card-body p-3">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-check text-success me-2"></i>Tugas Saya</h6>
        <div class="d-flex flex-column gap-2">
            @foreach($tugasSaya as $tugas)
            <div class="border rounded p-3" style="border-radius: 10px; background-color: rgba(25, 135, 84, 0.05);">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold text-dark">{{ $tugas->judul }}</div>
                        <div class="text-secondary small">
                            <i class="bi bi-building me-1"></i>{{ $tugas->fasilitas->nama_fasilitas ?? '-' }}
                        </div>
                        <div class="text-secondary small">
                            <i class="bi bi-geo-alt me-1"></i>{{ $tugas->fasilitas->lokasi ?? '-' }}
                        </div>
                    </div>
                    <span class="badge bg-success" style="font-size: 0.7rem;">Bergabung</span>
                </div>
                <div class="d-flex justify-content-between align-items-center small text-secondary mb-2">
                    <span><i class="bi bi-calendar me-1"></i>{{ $tugas->tanggal_tugas->format('d M Y H:i') }}</span>
                    <span class="badge bg-info bg-opacity-10 text-info">
                        Petugas: {{ $tugas->petugas_terdaftar }}/{{ $tugas->kuota_tim }}
                    </span>
                </div>
                <div class="small text-secondary mb-2">
                    {{ Str::limit($tugas->deskripsi, 80) }}
                </div>
                <form action="{{ route('petugas.tugas-inspeksi.leave', $tugas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari tugas ini?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="border-radius: 6px;">
                        <i class="bi bi-x-circle me-1"></i>Keluar Tim
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Tugas Tersedia -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-3">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-list-task text-success me-2"></i>Tugas Tersedia</h6>
        @if($tugasTersedia->isEmpty())
        <div class="text-center py-4">
            <p class="text-secondary mb-0 small">Belum ada tugas inspeksi yang tersedia.</p>
        </div>
        @else
        <div class="d-flex flex-column gap-2">
            @foreach($tugasTersedia as $tugas)
            @php
                $isJoined = $tugas->isUserJoined(auth()->id());
                $isFull = $tugas->isFull();
            @endphp
            <div class="border rounded p-3" style="border-radius: 10px;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold text-dark">{{ $tugas->judul }}</div>
                        <div class="text-secondary small">
                            <i class="bi bi-building me-1"></i>{{ $tugas->fasilitas->nama_fasilitas ?? '-' }}
                        </div>
                        <div class="text-secondary small">
                            <i class="bi bi-geo-alt me-1"></i>{{ $tugas->fasilitas->lokasi ?? '-' }}
                        </div>
                    </div>
                    @if($isFull)
                        <span class="badge bg-warning" style="font-size: 0.7rem;">Penuh</span>
                    @elseif($isJoined)
                        <span class="badge bg-success" style="font-size: 0.7rem;">Bergabung</span>
                    @else
                        <span class="badge bg-secondary" style="font-size: 0.7rem;">Tersedia</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center small text-secondary mb-2">
                    <span><i class="bi bi-calendar me-1"></i>{{ $tugas->tanggal_tugas->format('d M Y H:i') }}</span>
                    <span class="badge bg-info bg-opacity-10 text-info">
                        Petugas: {{ $tugas->petugas_terdaftar }}/{{ $tugas->kuota_tim }}
                    </span>
                </div>
                <div class="small text-secondary mb-2">
                    {{ Str::limit($tugas->deskripsi, 80) }}
                </div>
                @if($isJoined)
                    <form action="{{ route('petugas.tugas-inspeksi.leave', $tugas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari tugas ini?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="border-radius: 6px;">
                            <i class="bi bi-x-circle me-1"></i>Keluar Tim
                        </button>
                    </form>
                @elseif($isFull)
                    <button type="button" class="btn btn-sm btn-secondary w-100" disabled style="border-radius: 6px;">
                        <i class="bi bi-x-circle me-1"></i>Tugas Penuh
                    </button>
                @else
                    <form action="{{ route('petugas.tugas-inspeksi.join', $tugas->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success w-100" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a;">
                            <i class="bi bi-check-circle me-1"></i>Join/Ambil Tugas
                        </button>
                    </form>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
