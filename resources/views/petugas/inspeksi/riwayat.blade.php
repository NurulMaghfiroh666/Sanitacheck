@extends('layouts.petugas')

@section('title', 'Riwayat Inspeksi | SanitaCheck')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-success me-2"></i>Riwayat Inspeksi</h5>

<!-- Statistik Ringkasan -->
<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body text-center p-2">
                <div class="fw-bold fs-4 text-success">{{ $totalInspeksi }}</div>
                <div class="text-secondary" style="font-size: 0.7rem;">Total Inspeksi</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body text-center p-2">
                <div class="fw-bold fs-4 text-primary">{{ $inspeksiBulanIni }}</div>
                <div class="text-secondary" style="font-size: 0.7rem;">Bulan Ini</div>
            </div>
        </div>
    </div>
</div>

@if($riwayatInspeksi->isEmpty())
<div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
    <div class="card-body">
        <i class="bi bi-inbox text-secondary" style="font-size: 3rem;"></i>
        <p class="text-secondary mt-2 mb-0">Belum ada riwayat inspeksi.</p>
    </div>
</div>
@else
<div class="d-flex flex-column gap-2">
    @foreach($riwayatInspeksi as $inspeksi)
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-bold text-dark">{{ $inspeksi->fasilitas->nama_fasilitas ?? '-' }}</div>
                    <div class="text-secondary small"><i class="bi bi-geo-alt me-1"></i>{{ $inspeksi->fasilitas->lokasi ?? '-' }}</div>
                </div>
                @php
                    $kondisi = strtolower($inspeksi->kondisi_kebersihan ?? '');
                @endphp
                <span class="badge 
                    {{ $kondisi === 'baik' ? 'bg-success' : '' }}
                    {{ $kondisi === 'cukup' ? 'bg-warning text-dark' : '' }}
                    {{ $kondisi === 'buruk' ? 'bg-danger' : '' }}
                " style="font-size: 0.7rem;">{{ ucfirst($inspeksi->kondisi_kebersihan) }}</span>
            </div>
            
            <!-- Detail Inspeksi -->
            <div class="row mt-2 mb-2">
                <div class="col-4 text-center">
                    <div class="small text-secondary">Air</div>
                    @php $air = strtolower($inspeksi->ketersediaan_air ?? ''); @endphp
                    <i class="bi {{ ($air == 'ada' || $air == '1') ? 'bi-droplet-fill text-primary' : 'bi-droplet text-secondary' }}"></i>
                    <div style="font-size: 0.65rem;">{{ ($air == 'ada' || $air == '1') ? 'Ada' : 'Tidak' }}</div>
                </div>
                <div class="col-4 text-center">
                    <div class="small text-secondary">Sabun</div>
                    @php $sabun = strtolower($inspeksi->ketersediaan_sabun ?? ''); @endphp
                    <i class="bi {{ ($sabun == 'ada' || $sabun == '1') ? 'bi-hand-thumbs-up-fill text-success' : 'bi-hand-thumbs-down text-secondary' }}"></i>
                    <div style="font-size: 0.65rem;">{{ ($sabun == 'ada' || $sabun == '1') ? 'Ada' : 'Tidak' }}</div>
                </div>
                <div class="col-4 text-center">
                    <div class="small text-secondary">Bau</div>
                    @php $bau = strtolower($inspeksi->bau_tidak_sedap ?? ''); @endphp
                    <i class="bi {{ ($bau == 'ya' || $bau == '1') ? 'bi-exclamation-triangle-fill text-danger' : 'bi-check-circle-fill text-success' }}"></i>
                    <div style="font-size: 0.65rem;">{{ ($bau == 'ya' || $bau == '1') ? 'Berbau' : 'Aman' }}</div>
                </div>
            </div>

            @if($inspeksi->catatan)
            <div class="bg-light rounded p-2 mb-2">
                <div class="small text-secondary"><i class="bi bi-chat-left-text me-1"></i>{{ $inspeksi->catatan }}</div>
            </div>
            @endif
            
            <hr class="my-2">
            <div class="d-flex justify-content-between small text-secondary">
                <span><i class="bi bi-calendar3 me-1"></i>{{ $inspeksi->created_at->format('d M Y, H:i') }}</span>
                @php $statusTL = strtolower($inspeksi->status_tindak_lanjut ?? ''); @endphp
                <span class="fw-semibold 
                    {{ $statusTL == 'selesai' ? 'text-success' : '' }}
                    {{ $statusTL == 'menunggu' ? 'text-warning' : '' }}
                    {{ ($statusTL == 'aman') ? 'text-success' : '' }}
                    {{ str_contains($statusTL, 'perlu') ? 'text-danger' : '' }}
                    {{ $statusTL == 'dibatalkan' ? 'text-danger' : '' }}
                ">{{ ucfirst($inspeksi->status_tindak_lanjut) }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-3">
    {{ $riwayatInspeksi->links('pagination::bootstrap-5') }}
</div>
@endif
@endsection
