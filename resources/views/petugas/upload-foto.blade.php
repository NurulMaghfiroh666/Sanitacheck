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
        <div class="card border shadow-sm" style="border-radius: 12px;">
            <img src="{{ asset('storage/' . $photo->foto_path) }}" alt="Foto Inspeksi" class="card-img-top" style="height: 200px; object-fit: cover; border-radius: 12px 12px 0 0;">
            <div class="card-body p-3">
                @if($photo->keterangan)
                <div class="small mb-2" style="color: #333;">{{ $photo->keterangan }}</div>
                @endif
                <div class="small text-body-secondary mb-2">{{ $photo->created_at->format('d/m/Y H:i') }}</div>
                <form action="{{ route('petugas.delete-foto', $photo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="border-radius: 6px;">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
