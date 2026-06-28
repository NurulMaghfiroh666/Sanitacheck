<!-- Modal Detail Tugas -->
@foreach($tugasInspeksi as $tugas)
<div class="modal fade" id="detailTugasModal{{ $tugas->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Detail Tugas Inspeksi</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2"><strong>Judul:</strong> {{ $tugas->judul }}</div>
                <div class="mb-2"><strong>Fasilitas:</strong> {{ $tugas->fasilitas->nama_fasilitas ?? '-' }} ({{ $tugas->fasilitas->lokasi ?? '-' }})</div>
                <div class="mb-2"><strong>Waktu:</strong> {{ $tugas->tanggal_tugas->format('d M Y, H:i') }}</div>
                <div class="mb-2"><strong>Deskripsi:</strong> <span class="text-secondary">{{ $tugas->deskripsi ?: '-' }}</span></div>
                <div class="mb-2"><strong>Status:</strong> 
                    @if($tugas->status == 'tersedia')
                        <span class="badge bg-success bg-opacity-10 text-success">Tersedia</span>
                    @elseif($tugas->status == 'penuh')
                        <span class="badge bg-warning bg-opacity-10 text-warning">Penuh</span>
                    @elseif($tugas->status == 'selesai')
                        <span class="badge bg-secondary bg-opacity-10 text-secondary">Selesai</span>
                    @elseif($tugas->status == 'dibatalkan')
                        <span class="badge bg-danger bg-opacity-10 text-danger">Dibatalkan</span>
                    @endif
                </div>
                <div class="mb-2"><strong>Approval:</strong> 
                    @if($tugas->approval_status == 'pending')
                        <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                    @elseif($tugas->approval_status == 'approved')
                        <span class="badge bg-success bg-opacity-10 text-success">Approved</span>
                    @elseif($tugas->approval_status == 'rejected')
                        <span class="badge bg-danger bg-opacity-10 text-danger">Rejected</span>
                    @else
                        <span class="text-body-secondary">-</span>
                    @endif
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
                    </div>
                    @endif
                </div>
                @if($tugas->status == 'selesai' && $tugas->approval_status == 'pending')
                <div class="mt-3 pt-3 border-top">
                    <h6 class="fw-bold text-success mb-3"><i class="bi bi-file-earmark-check me-2"></i>Hasil Laporan Inspeksi</h6>
                    
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <div class="text-muted small">Kondisi Kebersihan</div>
                            <div class="fw-medium">{{ $tugas->kondisi_kebersihan }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Bau Tidak Sedap</div>
                            <div class="fw-medium">{{ $tugas->bau_tidak_sedap ? 'Ya' : 'Tidak' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Ketersediaan Air</div>
                            <div class="fw-medium">{{ $tugas->ketersediaan_air ? 'Ada' : 'Tidak Ada' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Ketersediaan Sabun</div>
                            <div class="fw-medium">{{ $tugas->ketersediaan_sabun ? 'Ada' : 'Tidak Ada' }}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Catatan Laporan</div>
                        <div class="bg-light p-2 rounded small">{{ $tugas->laporan_catatan }}</div>
                    </div>
                    @if($tugas->laporan_foto)
                    <div class="mb-3">
                        <div class="text-muted small mb-1">Foto Bukti</div>
                        <img src="{{ asset($tugas->laporan_foto) }}" class="img-fluid rounded border" alt="Foto Laporan" style="max-height: 150px;">
                    </div>
                    @endif
                </div>
                @endif
            </div>
            <div class="modal-footer border-top d-flex justify-content-between w-100">
                <button type="button" class="btn btn-secondary btn-sm" data-coreui-dismiss="modal">Tutup</button>
                
                @if($tugas->status == 'selesai' && $tugas->approval_status == 'pending')
                <div class="d-flex gap-2">
                    <form action="{{ route('tugas-inspeksi.reject-report', $tugas->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-danger btn-sm text-white"><i class="bi bi-x-circle me-1"></i>Tolak</button>
                    </form>
                    <form action="{{ route('tugas-inspeksi.approve-report', $tugas->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm text-white" style="background-color: #1b5e3a; border-color: #1b5e3a;"><i class="bi bi-check-circle me-1"></i>ACC (Setuju)</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
