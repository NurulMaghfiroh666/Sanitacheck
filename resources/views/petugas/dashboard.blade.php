@extends('layouts.petugas')

@section('title', 'Dashboard Petugas | SanitaCheck')

@section('content')
<div class="text-center py-4">
    <div class="mb-3">
        <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
    </div>
    <h4 class="fw-bold text-dark">Selamat Datang, {{ $petugas->nama_petugas ?? Auth::user()->name }}!</h4>
    <p class="text-secondary">Panel monitoring inspeksi sanitasi kamu.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body text-center p-3">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-success bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px;">
                    <i class="bi bi-calendar2-check text-success fs-4"></i>
                </div>
                <div class="fw-bold fs-3 text-dark">{{ $jadwalHariIni }}</div>
                <div class="text-secondary small">Jadwal Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body text-center p-3">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-primary bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px;">
                    <i class="bi bi-clipboard-check text-primary fs-4"></i>
                </div>
                <div class="fw-bold fs-3 text-dark">{{ $inspeksiSelesai }}</div>
                <div class="text-secondary small">Total Inspeksi</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body text-center p-3">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-warning bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px;">
                    <i class="bi bi-hourglass-split text-warning fs-4"></i>
                </div>
                <div class="fw-bold fs-3 text-dark">{{ $jadwalMenunggu }}</div>
                <div class="text-secondary small">Jadwal Menunggu</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body text-center p-3">
                <div class="d-flex justify-content-center align-items-center rounded-circle mx-auto mb-2" style="width: 50px; height: 50px; background-color: rgba(25, 135, 84, 0.1);">
                    <i class="bi bi-check-circle text-success fs-4"></i>
                </div>
                <div class="fw-bold fs-3 text-dark">{{ $jadwalSelesai }}</div>
                <div class="text-secondary small">Jadwal Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3" style="border-radius: 15px;">
    <div class="card-body p-3">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-calendar3 text-success me-2"></i>Jadwal Inspeksi Terbaru</h6>
        @if($jadwalTerbaru->isEmpty())
        <div class="text-center py-3">
            <p class="text-secondary mb-0 small">Belum ada jadwal inspeksi.</p>
        </div>
        @else
        <div class="d-flex flex-column gap-2">
            @foreach($jadwalTerbaru as $jadwal)
            <div class="border rounded p-3" style="border-radius: 10px;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold text-dark">{{ $jadwal->jenis_tugas }}</div>
                        <div class="text-secondary small">
                            <i class="bi bi-building me-1"></i>{{ $jadwal->fasilitas->nama_fasilitas ?? '-' }}
                        </div>
                        <div class="text-secondary small">
                            <i class="bi bi-geo-alt me-1"></i>{{ $jadwal->fasilitas->lokasi ?? '-' }}
                        </div>
                    </div>
                    @if($jadwal->status == 'Menunggu')
                    <span class="badge bg-secondary" style="font-size: 0.7rem;">Menunggu</span>
                    @elseif($jadwal->status == 'Berlangsung')
                    <span class="badge bg-primary" style="font-size: 0.7rem;">Berlangsung</span>
                    @elseif($jadwal->status == 'Selesai')
                    <span class="badge bg-success" style="font-size: 0.7rem;">Selesai</span>
                    @elseif($jadwal->status == 'Dibatalkan')
                    <span class="badge bg-danger" style="font-size: 0.7rem;">Dibatalkan</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between small text-secondary">
                    <span><i class="bi bi-calendar me-1"></i>{{ $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-' }} - {{ $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-' }}</span>
                    <a href="{{ route('petugas.jadwal') }}" class="text-success fw-semibold">Lihat Detail</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-3 text-center">
        <p class="text-secondary mb-0 small">
            <i class="bi bi-info-circle me-1"></i> Pilih menu <strong>Jadwal</strong> di bawah untuk melihat semua jadwal inspeksi.
        </p>
    </div>
</div>
@endsection
