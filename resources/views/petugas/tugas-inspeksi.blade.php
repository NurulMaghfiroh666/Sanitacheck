@extends('layouts.petugas')

@section('title', 'Tugas Inspeksi | SanitaCheck')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center border-bottom pb-2">
    <div>
        <h5 class="fw-bold mb-0" style="color: #1b5e3a;"><i class="bi bi-list-task me-2"></i>Tugas Inspeksi</h5>
        <p class="text-body-secondary mb-0" style="font-size: 0.8rem;">Ambil tugas inspeksi yang tersedia</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm py-2 px-3 mb-3" role="alert" style="border-radius: 8px; font-size: 0.85rem;">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm py-2 px-3 mb-3" role="alert" style="border-radius: 8px; font-size: 0.85rem;">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
</div>
@endif

@if($tugasSaya->isNotEmpty())
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-1">
        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;"><i class="bi bi-person-check text-success me-2"></i>Tugas Saya</h6>
    </div>
    <div class="card-body p-3">
        <div class="row g-3">
            @foreach($tugasSaya as $tugas)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between" style="background-color: rgba(25, 135, 84, 0.03); border-color: rgba(25, 135, 84, 0.2) !important;">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                            <div class="fw-bold text-dark text-truncate-2" style="font-size: 0.85rem; line-height: 1.3;">{{ $tugas->judul }}</div>
                            @if($tugas->status == 'selesai')
                                @if($tugas->approval_status == 'pending')
                                    <span class="badge bg-warning text-dark flex-shrink-0" style="font-size: 0.65rem;">Menunggu ACC</span>
                                @elseif($tugas->approval_status == 'rejected')
                                    <span class="badge bg-danger flex-shrink-0" style="font-size: 0.65rem;">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary flex-shrink-0" style="font-size: 0.65rem;">Selesai</span>
                                @endif
                            @else
                                <span class="badge bg-success flex-shrink-0" style="font-size: 0.65rem;">Bergabung</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 text-secondary" style="font-size: 0.75rem;">
                            <div class="text-truncate me-2">
                                <i class="bi bi-building me-1"></i>{{ $tugas->fasilitas->nama_fasilitas ?? '-' }}
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-event me-1"></i>{{ $tugas->tanggal_tugas->format('d M') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-auto">
                        <button type="button" class="btn btn-sm btn-outline-info flex-grow-1 btn-detail-tugas" data-tugas-id="saya-{{ $tugas->id }}" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                            <i class="bi bi-info-circle me-1"></i>Detail
                        </button>
                        <form action="{{ route('petugas.tugas-inspeksi.leave', $tugas->id) }}" method="POST" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 btn-keluar-tugas" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                                <i class="bi bi-box-arrow-right me-1"></i>Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-1">
        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;"><i class="bi bi-list-ul text-success me-2"></i>Semua Tugas Aktif</h6>
    </div>
    <div class="card-body p-3">
        @if($tugasTersedia->isEmpty())
        <div class="text-center py-4">
            <p class="text-secondary mb-0 small">Belum ada tugas inspeksi yang tersedia.</p>
        </div>
        @else
        <div class="row g-3">
            @foreach($tugasTersedia as $tugas)
            @php
                $isJoined = $tugas->isUserJoined(auth()->id());
                $isFull = $tugas->isFull();
            @endphp
            <div class="col-12 col-md-6 col-lg-4">
                <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                            <div class="fw-bold text-dark text-truncate-2" style="font-size: 0.85rem; line-height: 1.3;">{{ $tugas->judul }}</div>
                            <div class="flex-shrink-0">
                                @if($isFull)
                                    <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Penuh</span>
                                @elseif($isJoined)
                                    <span class="badge bg-success" style="font-size: 0.65rem;">Joined</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size: 0.65rem;">Tersedia</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 text-secondary" style="font-size: 0.75rem;">
                            <div class="text-truncate me-2">
                                <i class="bi bi-building me-1"></i>{{ $tugas->fasilitas->nama_fasilitas ?? '-' }}
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-event me-1"></i>{{ $tugas->tanggal_tugas->format('d M') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-auto">
                        <button type="button" class="btn btn-sm btn-outline-info flex-grow-1 btn-detail-tugas" data-tugas-id="semua-{{ $tugas->id }}" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                            <i class="bi bi-info-circle me-1"></i>Detail
                        </button>
                        
                        @if($isJoined)
                            <form action="{{ route('petugas.tugas-inspeksi.leave', $tugas->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 btn-keluar-tugas" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                                </button>
                            </form>
                        @elseif($isFull)
                            <button type="button" class="btn btn-sm btn-secondary flex-grow-1" disabled style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                                <i class="bi bi-x-circle me-1"></i>Penuh
                            </button>
                        @else
                            <form action="{{ route('petugas.tugas-inspeksi.join', $tugas->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success text-white w-100" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem; background-color: #1b5e3a; border-color: #1b5e3a;">
                                    <i class="bi bi-check-circle me-1"></i>Ambil
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@if(isset($riwayatTugas) && $riwayatTugas->isNotEmpty())
<div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-1">
        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;"><i class="bi bi-clock-history text-success me-2"></i>Riwayat Tugas Saya</h6>
    </div>
    <div class="card-body p-3">
        <div class="row g-3">
            @foreach($riwayatTugas as $tugas)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between bg-light">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                            <div class="fw-bold text-dark text-truncate-2" style="font-size: 0.85rem; line-height: 1.3;">{{ $tugas->judul }}</div>
                            <span class="badge bg-success flex-shrink-0" style="font-size: 0.65rem;">Selesai (ACC)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 text-secondary" style="font-size: 0.75rem;">
                            <div class="text-truncate me-2">
                                <i class="bi bi-building me-1"></i>{{ $tugas->fasilitas->nama_fasilitas ?? '-' }}
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-check me-1"></i>{{ $tugas->tanggal_tugas->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 btn-detail-tugas" data-tugas-id="riwayat-{{ $tugas->id }}" style="font-size: 0.75rem; border-radius: 6px; padding: 0.35rem 0.25rem;">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@section('modals')
@foreach($tugasSaya as $tugas)
<div class="modal fade" id="modalTugasSaya{{ $tugas->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-success"><i class="bi bi-info-circle me-2"></i>Detail Tugas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Judul:</strong> <br>{{ $tugas->judul }}</div>
                <div class="mb-2"><strong>Fasilitas:</strong> <br>{{ $tugas->fasilitas->nama_fasilitas ?? '-' }} ({{ $tugas->fasilitas->lokasi ?? '-' }})</div>
                <div class="mb-2"><strong>Waktu:</strong> <br>{{ $tugas->tanggal_tugas->format('d M Y, H:i') }}</div>
                <div class="mb-2"><strong>Deskripsi:</strong> <br><span class="text-secondary">{{ $tugas->deskripsi ?: '-' }}</span></div>
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Tim Petugas:</strong>
                        <span class="badge bg-info bg-opacity-10 text-info">{{ $tugas->petugas_terdaftar }}/{{ $tugas->kuota_tim }}</span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        @php
                            $sortedUsers = $tugas->users->sortByDesc(function($user) use ($tugas) {
                                return $user->id === $tugas->ketua_id ? 1 : 0;
                            });
                        @endphp
                        @forelse($sortedUsers as $user)
                            <li class="text-secondary mb-1" style="font-size: 0.8rem;">
                                <i class="bi bi-person-fill me-1"></i><span class="fw-medium">{{ $user->petugas->nama_petugas ?? $user->name }}</span>
                                @if($tugas->ketua_id == $user->id)
                                    <span class="badge bg-success ms-1" style="font-size: 0.65rem;"><i class="bi bi-star-fill me-1"></i>Ketua</span>
                                @elseif(!$tugas->ketua_id && $user->pivot->voted_ketua_id)
                                    <span class="badge bg-info text-white ms-1" style="font-size: 0.6rem;"><i class="bi bi-check-circle me-1"></i>Sudah Vote</span>
                                @elseif(!$tugas->ketua_id && !$user->pivot->voted_ketua_id)
                                    <span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">Belum Vote</span>
                                @endif
                            </li>
                        @empty
                            <li class="text-muted fst-italic">-</li>
                        @endforelse
                    </ul>
                    @php
                        $votingStatus = $tugas->voting_status;
                    @endphp
                    @if($votingStatus['status'] != 'not_applicable')
                    <div class="mt-2">
                        <div class="alert @if($votingStatus['status'] == 'unanimous') alert-success @elseif($votingStatus['status'] == 'conflict') alert-danger @else alert-info @endif py-2 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                            <i class="bi @if($votingStatus['status'] == 'unanimous') bi-check-circle-fill @elseif($votingStatus['status'] == 'conflict') bi-exclamation-triangle-fill @else bi-info-circle-fill @endif me-1"></i>{{ $votingStatus['message'] }}
                        </div>
                        @if($votingStatus['status'] == 'conflict' && $tugas->isUserJoined(auth()->id()))
                        <form action="{{ route('petugas.tugas-inspeksi.reset-voting', $tugas->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning w-100" style="font-size: 0.7rem; border-radius: 6px;">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset Voting
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                    @if($tugas->isUserJoined(auth()->id()) && $tugas->petugas_terdaftar > 1 && $votingStatus['status'] != 'unanimous' && !$tugas->hasUserVoted(auth()->id()))
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalPilihKetua{{ $tugas->id }}" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-person-plus me-1"></i>Pilih Ketua
                        </button>
                    </div>
                    @endif
                    @if($tugas->ketua_id == auth()->id())
                    <div class="mt-3 pt-2 border-top">
                        @if($tugas->status == 'selesai' && $tugas->approval_status == 'pending')
                        <div class="alert alert-warning py-2 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-hourglass-split me-1"></i><strong>Menunggu ACC Admin</strong><br>
                            Laporan sedang ditinjau.
                        </div>
                        @else
                            @if($tugas->approval_status == 'rejected')
                            <div class="alert alert-danger py-2 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                                <i class="bi bi-x-circle-fill me-1"></i><strong>Laporan Ditolak!</strong><br>
                                Silakan perbaiki dan kirim ulang laporan.
                            </div>
                            @else
                            <div class="alert alert-info py-2 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                                <i class="bi bi-star-fill me-1"></i><strong>Anda adalah Ketua Tim</strong><br>
                                Anda bertanggung jawab untuk mengirim laporan inspeksi.
                            </div>
                            @endif
                            <button type="button" class="btn btn-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalLaporan{{ $tugas->id }}" style="background-color: #1b5e3a; border-color: #1b5e3a; border-radius: 6px;">
                                <i class="bi bi-file-earmark-plus me-1"></i>{{ $tugas->approval_status == 'rejected' ? 'Edit & Kirim Ulang' : 'Kirim Laporan' }}
                            </button>
                        @endif
                    </div>
                    @elseif($tugas->ketua_id && $tugas->ketua_id != auth()->id())
                    <div class="mt-3 pt-2 border-top">
                        @if($tugas->status == 'selesai' && $tugas->approval_status == 'pending')
                        <div class="alert alert-warning py-2 px-2 text-center" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-hourglass-split me-1"></i>Laporan tim sedang menunggu ACC Admin.
                        </div>
                        @elseif($tugas->approval_status == 'rejected')
                        <div class="alert alert-danger py-2 px-2 text-center" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-x-circle-fill me-1"></i>Laporan ditolak Admin. Ketua sedang merevisi.
                        </div>
                        @else
                        <div class="alert alert-secondary py-2 px-2 text-center" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-person-check me-1"></i><strong>Anda adalah Anggota Tim</strong><br>
                            Ketua tim sedang bertanggung jawab mengisi form inspeksi.
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($tugasTersedia as $tugas)
@php
    $isJoined = $tugas->isUserJoined(auth()->id());
    $isFull = $tugas->isFull();
@endphp
<div class="modal fade" id="modalSemuaTugas{{ $tugas->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-success"><i class="bi bi-info-circle me-2"></i>Detail Tugas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Judul:</strong> <br>{{ $tugas->judul }}</div>
                <div class="mb-2"><strong>Fasilitas:</strong> <br>{{ $tugas->fasilitas->nama_fasilitas ?? '-' }} ({{ $tugas->fasilitas->lokasi ?? '-' }})</div>
                <div class="mb-2"><strong>Waktu:</strong> <br>{{ $tugas->tanggal_tugas->format('d M Y, H:i') }}</div>
                <div class="mb-2"><strong>Deskripsi:</strong> <br><span class="text-secondary">{{ $tugas->deskripsi ?: '-' }}</span></div>
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Tim Petugas:</strong>
                        <span class="badge bg-info bg-opacity-10 text-info">{{ $tugas->petugas_terdaftar }}/{{ $tugas->kuota_tim }}</span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        @php
                            $sortedUsers = $tugas->users->sortByDesc(function($user) use ($tugas) {
                                return $user->id === $tugas->ketua_id ? 1 : 0;
                            });
                        @endphp
                        @forelse($sortedUsers as $user)
                            <li class="text-secondary mb-1" style="font-size: 0.8rem;">
                                <i class="bi bi-person-fill me-1"></i><span class="fw-medium">{{ $user->petugas->nama_petugas ?? $user->name }}</span>
                                @if($tugas->ketua_id == $user->id)
                                    <span class="badge bg-success ms-1" style="font-size: 0.65rem;"><i class="bi bi-star-fill me-1"></i>Ketua</span>
                                @elseif(!$tugas->ketua_id && $user->pivot->voted_ketua_id)
                                    <span class="badge bg-info text-white ms-1" style="font-size: 0.6rem;"><i class="bi bi-check-circle me-1"></i>Sudah Vote</span>
                                @elseif(!$tugas->ketua_id && !$user->pivot->voted_ketua_id)
                                    <span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">Belum Vote</span>
                                @endif
                            </li>
                        @empty
                            <li class="text-muted fst-italic">-</li>
                        @endforelse
                    </ul>
                    @php
                        $votingStatus = $tugas->voting_status;
                    @endphp
                    @if($votingStatus['status'] != 'not_applicable')
                    <div class="mt-2">
                        <div class="alert @if($votingStatus['status'] == 'unanimous') alert-success @elseif($votingStatus['status'] == 'conflict') alert-danger @else alert-info @endif py-2 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                            <i class="bi @if($votingStatus['status'] == 'unanimous') bi-check-circle-fill @elseif($votingStatus['status'] == 'conflict') bi-exclamation-triangle-fill @else bi-info-circle-fill @endif me-1"></i>{{ $votingStatus['message'] }}
                        </div>
                        @if($votingStatus['status'] == 'conflict' && $isJoined)
                        <form action="{{ route('petugas.tugas-inspeksi.reset-voting', $tugas->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning w-100" style="font-size: 0.7rem; border-radius: 6px;">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset Voting
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                    @if($isJoined && $tugas->petugas_terdaftar > 1 && $votingStatus['status'] != 'unanimous' && !$tugas->hasUserVoted(auth()->id()))
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalPilihKetuaSemua{{ $tugas->id }}" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="bi bi-person-plus me-1"></i>Pilih Ketua
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-3 pb-3">
                @if($isJoined)
                    <form action="{{ route('petugas.tugas-inspeksi.leave', $tugas->id) }}" method="POST" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 btn-keluar-tugas" style="border-radius: 6px;">Keluar dari Tim</button>
                    </form>
                @elseif(!$isFull)
                    <form action="{{ route('petugas.tugas-inspeksi.join', $tugas->id) }}" method="POST" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success text-white w-100" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a;">Bergabung Sekarang</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="modalConfirmKeluar" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-circle text-warning" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Keluar dari Tim?</h6>
                <p class="text-secondary small mb-4">Apakah Anda yakin ingin keluar dari tugas inspeksi ini? Posisi Anda mungkin akan digantikan oleh petugas lain.</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">Batal</button>
                    <button type="button" class="btn btn-danger flex-grow-1" id="btnConfirmKeluar" style="border-radius: 8px; font-size: 0.85rem;">Ya, Keluar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Submit Report for Ketua -->
@foreach($tugasSaya as $tugas)
@if($tugas->ketua_id == auth()->id())
<div class="modal fade" id="modalLaporan{{ $tugas->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-success"><i class="bi bi-file-earmark-plus me-2"></i>Kirim Laporan Inspeksi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <form action="{{ route('tugas-inspeksi.submit-report', $tugas->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-3" style="font-size: 0.8rem;">
                    <div class="mb-2"><strong>Tugas:</strong> {{ $tugas->judul }}</div>
                    <div class="mb-2"><strong>Fasilitas:</strong> {{ $tugas->fasilitas->nama_fasilitas ?? '-' }}</div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kondisi Kebersihan</label>
                        <select name="kondisi_kebersihan" class="form-select form-select-sm" required>
                            <option value="">Pilih kondisi</option>
                            <option value="Baik" {{ $tugas->kondisi_kebersihan == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Cukup" {{ $tugas->kondisi_kebersihan == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                            <option value="Buruk" {{ $tugas->kondisi_kebersihan == 'Buruk' ? 'selected' : '' }}>Buruk</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Ketersediaan Air</label>
                            <select name="ketersediaan_air" class="form-select form-select-sm" required>
                                <option value="">Pilih</option>
                                <option value="1" {{ $tugas->ketersediaan_air === '1' || $tugas->ketersediaan_air === 1 ? 'selected' : '' }}>Ada</option>
                                <option value="0" {{ $tugas->ketersediaan_air === '0' || $tugas->ketersediaan_air === 0 ? 'selected' : '' }}>Tidak Ada</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Ketersediaan Sabun</label>
                            <select name="ketersediaan_sabun" class="form-select form-select-sm" required>
                                <option value="">Pilih</option>
                                <option value="1" {{ $tugas->ketersediaan_sabun === '1' || $tugas->ketersediaan_sabun === 1 ? 'selected' : '' }}>Ada</option>
                                <option value="0" {{ $tugas->ketersediaan_sabun === '0' || $tugas->ketersediaan_sabun === 0 ? 'selected' : '' }}>Tidak Ada</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Bau Tidak Sedap?</label>
                        <select name="bau_tidak_sedap" class="form-select form-select-sm" required>
                            <option value="">Pilih</option>
                            <option value="1" {{ $tugas->bau_tidak_sedap === '1' || $tugas->bau_tidak_sedap === 1 ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ $tugas->bau_tidak_sedap === '0' || $tugas->bau_tidak_sedap === 0 ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan Laporan</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="3" required placeholder="Jelaskan hasil inspeksi (min. 10 karakter)">{{ $tugas->laporan_catatan }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Foto Bukti (Opsional)</label>
                        @if($tugas->laporan_foto)
                            <div class="mb-2">
                                <img src="{{ asset($tugas->laporan_foto) }}" alt="Foto Bukti Sebelumnya" class="img-thumbnail" style="max-height: 100px;">
                                <div class="small text-muted mt-1">Pilih foto baru untuk mengganti foto sebelumnya.</div>
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control form-control-sm" accept="image/jpeg,image/png">
                        <small class="text-secondary">Format: JPG, PNG (Max 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer border-top pt-0 px-3 pb-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" style="background-color: #1b5e3a; border-color: #1b5e3a; border-radius: 6px;">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

<!-- Modal Pilih Ketua for Tugas Saya -->
@foreach($tugasSaya as $tugas)
@if($tugas->isUserJoined(auth()->id()) && $tugas->petugas_terdaftar > 1)
<div class="modal fade" id="modalPilihKetua{{ $tugas->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-primary"><i class="bi bi-person-plus me-2"></i>Pilih Ketua Tim</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Tugas:</strong> {{ $tugas->judul }}</div>
                <div class="mb-3 text-muted small">Pilih salah satu anggota tim untuk menjadi ketua:</div>
                <div class="list-group list-group-flush">
                    @forelse($tugas->users as $user)
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="showConfirmKetua({{ $tugas->id }}, {{ $user->id }}, '{{ $user->petugas->nama_petugas ?? $user->name }}')" style="font-size: 0.8rem;">
                            <span>
                                <i class="bi bi-person-fill me-2"></i>{{ $user->petugas->nama_petugas ?? $user->name }}
                            </span>
                            @if($tugas->ketua_id == $user->id)
                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Ketua</span>
                            @endif
                        </button>
                    @empty
                        <div class="text-muted fst-italic text-center py-2">-</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pilih Ketua for Tugas Saya -->
<div class="modal fade" id="modalConfirmKetua{{ $tugas->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-person-check text-primary" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Pilih Ketua Tim?</h6>
                <p class="text-secondary small mb-4">Apakah Anda yakin ingin memilih <strong id="ketuaName{{ $tugas->id }}"></strong> sebagai ketua tim?</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">Batal</button>
                    <button type="button" class="btn btn-primary flex-grow-1" id="btnConfirmKetua{{ $tugas->id }}" style="border-radius: 8px; font-size: 0.85rem;">Ya, Pilih</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

<!-- Modal Pilih Ketua for Semua Tugas -->
@foreach($tugasTersedia as $tugas)
@php
    $isJoined = $tugas->isUserJoined(auth()->id());
@endphp
@if($isJoined && $tugas->petugas_terdaftar > 1)
<div class="modal fade" id="modalPilihKetuaSemua{{ $tugas->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-primary"><i class="bi bi-person-plus me-2"></i>Pilih Ketua Tim</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Tugas:</strong> {{ $tugas->judul }}</div>
                <div class="mb-3 text-muted small">Pilih salah satu anggota tim untuk menjadi ketua:</div>
                <div class="list-group list-group-flush">
                    @forelse($tugas->users as $user)
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="showConfirmKetua({{ $tugas->id }}, {{ $user->id }}, '{{ $user->petugas->nama_petugas ?? $user->name }}')" style="font-size: 0.8rem;">
                            <span>
                                <i class="bi bi-person-fill me-2"></i>{{ $user->petugas->nama_petugas ?? $user->name }}
                            </span>
                            @if($tugas->ketua_id == $user->id)
                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Ketua</span>
                            @endif
                        </button>
                    @empty
                        <div class="text-muted fst-italic text-center py-2">-</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pilih Ketua for Semua Tugas -->
<div class="modal fade" id="modalConfirmKetuaSemua{{ $tugas->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-person-check text-primary" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Pilih Ketua Tim?</h6>
                <p class="text-secondary small mb-4">Apakah Anda yakin ingin memilih <strong id="ketuaNameSemua{{ $tugas->id }}"></strong> sebagai ketua tim?</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">Batal</button>
                    <button type="button" class="btn btn-primary flex-grow-1" id="btnConfirmKetuaSemua{{ $tugas->id }}" style="border-radius: 8px; font-size: 0.85rem;">Ya, Pilih</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

<!-- Modal Detail Riwayat Tugas -->
@if(isset($riwayatTugas))
@foreach($riwayatTugas as $tugas)
<div class="modal fade" id="modalRiwayatTugas{{ $tugas->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-success"><i class="bi bi-clock-history me-2"></i>Detail Riwayat</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="mb-2"><strong>Judul:</strong> <br>{{ $tugas->judul }}</div>
                <div class="mb-2"><strong>Fasilitas:</strong> <br>{{ $tugas->fasilitas->nama_fasilitas ?? '-' }}</div>
                <div class="mb-2"><strong>Waktu:</strong> <br>{{ $tugas->tanggal_tugas->format('d M Y, H:i') }}</div>
                
                <div class="mt-3 pt-2 border-top">
                    <h6 class="fw-bold mb-2 small text-success">Hasil Laporan</h6>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Kondisi</div>
                            <div class="fw-medium">{{ $tugas->kondisi_kebersihan }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Bau</div>
                            <div class="fw-medium">{{ $tugas->bau_tidak_sedap ? 'Ya' : 'Tidak' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Air</div>
                            <div class="fw-medium">{{ $tugas->ketersediaan_air ? 'Ada' : 'Tidak Ada' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size: 0.7rem;">Sabun</div>
                            <div class="fw-medium">{{ $tugas->ketersediaan_sabun ? 'Ada' : 'Tidak Ada' }}</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted" style="font-size: 0.7rem;">Catatan:</div>
                        <div class="bg-light p-2 rounded" style="font-size: 0.75rem;">{{ $tugas->laporan_catatan }}</div>
                    </div>
                </div>
                
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Tim Petugas:</strong>
                        <span class="badge bg-info bg-opacity-10 text-info">{{ $tugas->petugas_terdaftar }}/{{ $tugas->kuota_tim }}</span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        @php
                            $sortedUsers = $tugas->users->sortByDesc(function($user) use ($tugas) {
                                return $user->id === $tugas->ketua_id ? 1 : 0;
                            });
                        @endphp
                        @forelse($sortedUsers as $user)
                            <li class="text-secondary mb-1" style="font-size: 0.8rem;">
                                <i class="bi bi-person-fill me-1"></i><span class="fw-medium">{{ $user->petugas->nama_petugas ?? $user->name }}</span>
                                @if($tugas->ketua_id == $user->id)
                                    <span class="badge bg-success ms-1" style="font-size: 0.65rem;"><i class="bi bi-star-fill me-1"></i>Ketua</span>
                                @endif
                            </li>
                        @empty
                            <li class="text-muted fst-italic">-</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-3 pb-3">
                <button type="button" class="btn btn-sm btn-secondary w-100" data-bs-dismiss="modal" style="border-radius: 6px;">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-detail-tugas').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var tugasId = this.getAttribute('data-tugas-id');
            var prefix = tugasId.startsWith('saya-') ? 'modalTugasSaya' : (tugasId.startsWith('riwayat-') ? 'modalRiwayatTugas' : 'modalSemuaTugas');
            var id = tugasId.replace('saya-', '').replace('semua-', '').replace('riwayat-', '');
            var modalEl = document.getElementById(prefix + id);
            if (modalEl) {
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
    });

    var formToSubmit = null;
    var modalConfirmEl = document.getElementById('modalConfirmKeluar');
    var confirmModal = null;
    
    if (modalConfirmEl) {
        confirmModal = new bootstrap.Modal(modalConfirmEl);
    }

    document.querySelectorAll('.btn-keluar-tugas').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            formToSubmit = this.closest('form');
            
            var parentModal = this.closest('.modal');
            if(parentModal && parentModal.id !== 'modalConfirmKeluar') {
                var bsModal = bootstrap.Modal.getInstance(parentModal);
                if(bsModal) bsModal.hide();
            }

            if (confirmModal) {
                setTimeout(function() {
                    confirmModal.show();
                }, 300);
            }
        });
    });

    if (document.getElementById('btnConfirmKeluar')) {
        document.getElementById('btnConfirmKeluar').addEventListener('click', function() {
            if(formToSubmit) {
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                this.disabled = true;
                formToSubmit.submit();
            }
        });
    }

    var selectedKetua = { tugasId: null, userId: null, userName: null };

    window.showConfirmKetua = function(tugasId, userId, userName) {
        selectedKetua = { tugasId: tugasId, userId: userId, userName: userName };
        
        // Close the pilih ketua modal
        var pilihKetuaModal = document.getElementById('modalPilihKetua' + tugasId) || document.getElementById('modalPilihKetuaSemua' + tugasId);
        if (pilihKetuaModal) {
            var bsModal = bootstrap.Modal.getInstance(pilihKetuaModal);
            if (bsModal) bsModal.hide();
        }

        // Show confirm modal
        var confirmModalId = document.getElementById('modalPilihKetua' + tugasId) ? 'modalConfirmKetua' + tugasId : 'modalConfirmKetuaSemua' + tugasId;
        var ketuaNameId = document.getElementById('modalPilihKetua' + tugasId) ? 'ketuaName' + tugasId : 'ketuaNameSemua' + tugasId;
        
        document.getElementById(ketuaNameId).textContent = userName;
        
        var confirmModal = document.getElementById(confirmModalId);
        if (confirmModal) {
            var bsConfirmModal = new bootstrap.Modal(confirmModal);
            bsConfirmModal.show();
        }
    };

    function confirmSelectKetua() {
        if (!selectedKetua.tugasId) return;

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/area-petugas/tugas-inspeksi/' + selectedKetua.tugasId + '/set-ketua';
        
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            var csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }
        
        var methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
        
        var ketuaInput = document.createElement('input');
        ketuaInput.type = 'hidden';
        ketuaInput.name = 'ketua_id';
        ketuaInput.value = selectedKetua.userId;
        form.appendChild(ketuaInput);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Attach event listeners to confirm buttons
    document.querySelectorAll('[id^="btnConfirmKetua"]').forEach(function(btn) {
        btn.addEventListener('click', confirmSelectKetua);
    });
});
</script>
@endpush