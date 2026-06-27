@extends('layouts.petugas')

@section('title', 'Upload Foto Inspeksi | SanitaCheck')

@section('content')
<div class="mb-3">
    <a href="{{ route('petugas.jadwal') }}" class="text-decoration-none text-body-secondary small">
        &larr; Kembali ke Jadwal
    </a>
</div>

<h5 class="fw-bold mb-3"><i class="bi bi-camera text-success me-2"></i>Upload Foto Inspeksi</h5>

<div class="card border shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-4">
        <div class="mb-4">
            <h6 class="fw-bold mb-2" style="color: #333;">Detail Jadwal</h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="small text-body-secondary">Jenis Tugas</div>
                    <div class="small fw-semibold" style="color: #333;">{{ $jadwal->jenis_tugas }}</div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="small text-body-secondary">Fasilitas</div>
                    <div class="small fw-semibold" style="color: #333;">{{ $jadwal->fasilitas->nama_fasilitas ?? '-' }} ({{ $jadwal->fasilitas->lokasi ?? '-' }})</div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="small text-body-secondary">Periode</div>
                    <div class="small fw-semibold" style="color: #333;">
                        {{ $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-' }} s/d {{ $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-' }}
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="small text-body-secondary">Status</div>
                    <div class="small fw-semibold">
                        @if($jadwal->status == 'Menunggu')
                        <span class="badge bg-secondary" style="font-size: 0.75rem;">Menunggu</span>
                        @elseif($jadwal->status == 'Berlangsung')
                        <span class="badge bg-primary" style="font-size: 0.75rem;">Berlangsung</span>
                        @elseif($jadwal->status == 'Selesai')
                        <span class="badge bg-success" style="font-size: 0.75rem;">Selesai</span>
                        @elseif($jadwal->status == 'Dibatalkan')
                        <span class="badge bg-danger" style="font-size: 0.75rem;">Dibatalkan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('petugas.store-foto', $jadwal->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small" style="color: #333;">Foto Inspeksi</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required>
                <div class="form-text small text-body-secondary">Format: JPEG, PNG, JPG, GIF (Maks. 5MB)</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small" style="color: #333;">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan untuk foto ini..."></textarea>
            </div>
            <button type="submit" class="btn btn-success fw-semibold" style="border-radius: 8px; background-color: #1b5e3a; border-color: #1b5e3a;">
                <i class="bi bi-upload me-1"></i> Upload Foto
            </button>
        </form>
    </div>
</div>

<h6 class="fw-bold mb-3" style="color: #333;">Foto yang Sudah Diunggah</h6>

@if($jadwal->photos->isEmpty())
<div class="text-center py-5 bg-light rounded" style="border-radius: 12px;">
    <i class="bi bi-images text-secondary" style="font-size: 3rem;"></i>
    <p class="text-muted mt-2 mb-0">Belum ada foto yang diunggah.</p>
</div>
@else
<div class="row">
    @foreach($jadwal->photos as $photo)
    <div class="col-md-4 mb-3">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <img src="{{ asset('storage/' . $photo->foto_path) }}" alt="Foto Inspeksi" class="card-img-top" style="height: 200px; object-fit: cover; border-radius: 12px 12px 0 0; cursor: pointer;" onclick="openPreview('{{ asset('storage/' . $photo->foto_path) }}')">
            <div class="card-body p-3 d-flex flex-column">
                @if($photo->keterangan)
                <div class="small mb-2" style="color: #333;">{{ $photo->keterangan }}</div>
                @endif
                <div class="small text-body-secondary mb-3">{{ $photo->created_at->format('d/m/Y H:i') }}</div>
                <div class="mt-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100" style="border-radius: 6px;" onclick="confirmDeleteFoto('{{ route('petugas.delete-foto', $photo->id) }}')">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Modal Konfirmasi Hapus Foto -->
<div class="modal fade" id="modalConfirmDeleteFoto" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-circle-fill" style="font-size: 3.5rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Hapus Foto?</h6>
                <p class="text-body-secondary small mb-4">Foto ini akan dihapus permanen dan tidak bisa dikembalikan.</p>
                <form id="formDeleteFoto" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem; font-weight: 600;">Batal</button>
                        <button type="submit" class="btn btn-danger flex-grow-1" style="border-radius: 8px; font-size: 0.85rem; font-weight: 600;">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Foto (Lightbox) -->
<div class="modal fade" id="modalPreviewFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
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

@endsection

@push('scripts')
<script>
    // Preview image
    function openPreview(src) {
        document.getElementById('previewImage').src = src;
        document.getElementById('downloadImageBtn').href = src;
        var modal = new bootstrap.Modal(document.getElementById('modalPreviewFoto'));
        modal.show();
    }
    
    // Konfirmasi Hapus Foto
    function confirmDeleteFoto(url) {
        document.getElementById('formDeleteFoto').action = url;
        var modal = new bootstrap.Modal(document.getElementById('modalConfirmDeleteFoto'));
        modal.show();
    }
</script>
@endpush
