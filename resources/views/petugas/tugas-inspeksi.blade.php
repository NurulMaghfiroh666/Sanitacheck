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
                            <span class="badge bg-success flex-shrink-0" style="font-size: 0.65rem;">Bergabung</span>
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
                        @forelse($tugas->users as $user)
                            <li class="text-secondary mb-1"><i class="bi bi-person-fill me-1"></i>{{ $user->petugas->nama_petugas ?? $user->name }}</li>
                        @empty
                            <li class="text-muted fst-italic">-</li>
                        @endforelse
                    </ul>
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
                        @forelse($tugas->users as $user)
                            <li class="text-secondary mb-1"><i class="bi bi-person-fill me-1"></i>{{ $user->petugas->nama_petugas ?? $user->name }}</li>
                        @empty
                            <li class="text-muted fst-italic">-</li>
                        @endforelse
                    </ul>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-detail-tugas').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var tugasId = this.getAttribute('data-tugas-id');
            var prefix = tugasId.startsWith('saya-') ? 'modalTugasSaya' : 'modalSemuaTugas';
            var id = tugasId.replace('saya-', '').replace('semua-', '');
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
});
</script>
@endpush