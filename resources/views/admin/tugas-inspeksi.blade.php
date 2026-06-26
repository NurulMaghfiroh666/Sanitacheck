@extends('layouts.admin')

@section('title', 'Tugas Inspeksi')

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
    <div>
        <h4 class="fw-bold mb-0" style="color: #1b5e3a;">Tugas Inspeksi</h4>
        <p class="text-body-secondary mb-0 small">Kelola penugasan inspeksi tim</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success text-white fw-bold btn-sm shadow-sm px-3" data-coreui-toggle="modal" data-coreui-target="#addTugasModal" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a;">
            + Tambah Tugas
        </button>
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

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="{{ request()->url() }}" method="GET" class="row g-2 mb-3 align-items-end">
    <div class="col-md-4">
        <div class="input-group input-group-sm shadow-sm border rounded" style="overflow: hidden;">
            <input type="text" name="search" class="form-control border-0 bg-body text-body" placeholder="Cari tugas, fasilitas..." value="{{ request('search') }}">
            <button type="submit" class="input-group-text bg-body border-0 text-body-secondary">
                <svg class="icon" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.72 113.6-13.67 10.33 85.08 20.35 204.3c8.11 96.53 87.57 173.3 184.2 178.6 44.59 2.45 86.82-10.42 121.7-33.82l119.5 119.5c15.6 15.6 40.9 15.6 56.5 0l-1.95-24.9zM224 336c-70.7 0-128-57.3-128-128s57.3-128 128-128 128 57.3 128 128-57.3 128-128 128z"/>
                </svg>
            </button>
        </div>
    </div>
    
    <div class="col-md-8 d-flex gap-2 justify-content-md-end justify-content-start flex-wrap">
        @if(request('search'))
            <div>
                <a href="{{ request()->url() }}" class="btn btn-sm btn-secondary d-flex align-items-center fw-bold shadow-sm" style="border-radius: 6px;">
                    RESET
                </a>
            </div>
        @endif
        <div>
            <button type="submit" class="btn btn-sm btn-outline-success d-flex align-items-center fw-bold shadow-sm" style="border-radius: 6px; border-color: #1b5e3a; color: #1b5e3a; background-color: transparent;">
                <svg class="icon me-1" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M256 48A208 208 0 1 0 464 256 208 208 0 0 0 256 48zm0 384A176 176 0 1 1 432 256 176 176 0 0 1 256 432z" />
                    <path fill="currentColor" d="M344.4 167.6a16 16 0 0 0-22.6 0l-91.8 91.8L190.6 220a16 16 0 1 0-22.6 22.6l50.6 50.6a16 16 0 0 0 22.6 0l103.2-103.2a16 16 0 0 0 0-22.4z" />
                </svg>
                TAMPILKAN
            </button>
        </div>
    </div>
</form>

<div class="card border shadow-sm adaptive-card" style="border-radius: 10px;">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-2">
        <h6 class="mb-0 fw-bold text-success"><i class="bi bi-clipboard-check me-2"></i>Daftar Tugas Inspeksi</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-borderless table-hover align-middle mb-0 current-theme-table">
                <thead style="border-bottom: 1px solid var(--cui-border-color); background-color: var(--cui-tertiary-bg);">
                    <tr>
                        <th class="text-center text-success py-1 px-3" style="font-size: 0.75rem;">No</th>
                        <th class="text-success py-1" style="font-size: 0.75rem;">Judul Tugas</th>
                        <th class="text-success py-1" style="font-size: 0.75rem;">Fasilitas</th>
                        <th class="text-success py-1" style="font-size: 0.75rem;">Tanggal Tugas</th>
                        <th class="text-center text-success py-1" style="font-size: 0.75rem;">Kuota Tim</th>
                        <th class="text-center text-success py-1" style="font-size: 0.75rem;">Status</th>
                        <th class="text-center text-success py-1 px-3" style="font-size: 0.75rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tugasInspeksi as $index => $tugas)
                        <tr>
                            <td class="text-center py-2 px-3" style="font-size: 0.8rem;">
                                {{ ($tugasInspeksi->current_page() - 1) * $tugasInspeksi->perPage() + $index + 1 }}
                            </td>
                            <td class="py-2" style="font-size: 0.8rem;">
                                <div class="fw-bold">{{ $tugas->judul }}</div>
                                <div class="text-body-secondary small">{{ Str::limit($tugas->deskripsi, 50) }}</div>
                            </td>
                            <td class="py-2" style="font-size: 0.8rem;">
                                {{ $tugas->fasilitas->nama_fasilitas ?? '-' }}
                            </td>
                            <td class="py-2" style="font-size: 0.8rem;">
                                {{ $tugas->tanggal_tugas->format('d M Y H:i') }}
                            </td>
                            <td class="text-center py-2" style="font-size: 0.8rem;">
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $tugas->petugas_terdaftar }}/{{ $tugas->kuota_tim }}
                                </span>
                            </td>
                            <td class="text-center py-2" style="font-size: 0.8rem;">
                                @if($tugas->status == 'tersedia')
                                    <span class="badge bg-success bg-opacity-10 text-success">Tersedia</span>
                                @elseif($tugas->status == 'penuh')
                                    <span class="badge bg-warning bg-opacity-10 text-warning">Penuh</span>
                                @elseif($tugas->status == 'selesai')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">Selesai</span>
                                @endif
                            </td>
                            <td class="text-center py-2 px-3" style="font-size: 0.8rem;">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1" data-coreui-toggle="modal" data-coreui-target="#editTugasModal{{ $tugas->id }}" style="border-radius: 4px;">
                                    <svg class="icon" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 5.4 506.5s12.5 12.3 21.2 12.3h120.3c10.9 0 21.4-4.3 29.2-12l233.4-233.4L291.7 90.3z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('tugas-inspeksi.destroy', $tugas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 4px;">
                                        <svg class="icon" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path fill="currentColor" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.4 467c1.6 25.3 22.6 45 47.9 45H346.7c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-body-secondary">
                                Belum ada data tugas inspeksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent border-top py-2">
            {{ $tugasInspeksi->links() }}
        </div>
    </div>
</div>

<!-- Modal Add Tugas -->
<div class="modal fade" id="addTugasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Tambah Tugas Inspeksi</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tugas-inspeksi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Fasilitas</label>
                        <select name="fasilitas_id" class="form-select form-select-sm" required>
                            <option value="">Pilih Fasilitas</option>
                            @foreach($fasilitasList as $fasilitas)
                                <option value="{{ $fasilitas->id }}">{{ $fasilitas->nama_fasilitas }} - {{ $fasilitas->lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Tugas</label>
                        <input type="text" name="judul" class="form-control form-control-sm" required placeholder="Masukkan judul tugas">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control form-control-sm" rows="3" placeholder="Deskripsi tugas (opsional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kuota Tim (1-10 orang)</label>
                        <input type="number" name="kuota_tim" class="form-control form-control-sm" min="1" max="10" required value="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Tugas</label>
                        <input type="datetime-local" name="tanggal_tugas" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-coreui-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" style="background-color: #1b5e3a; border-color: #1b5e3a;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Tugas -->
@foreach($tugasInspeksi as $tugas)
<div class="modal fade" id="editTugasModal{{ $tugas->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Edit Tugas Inspeksi</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tugas-inspeksi.update', $tugas->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Fasilitas</label>
                        <select name="fasilitas_id" class="form-select form-select-sm" required>
                            <option value="">Pilih Fasilitas</option>
                            @foreach($fasilitasList as $fasilitas)
                                <option value="{{ $fasilitas->id }}" {{ $tugas->fasilitas_id == $fasilitas->id ? 'selected' : '' }}>{{ $fasilitas->nama_fasilitas }} - {{ $fasilitas->lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Tugas</label>
                        <input type="text" name="judul" class="form-control form-control-sm" required value="{{ $tugas->judul }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control form-control-sm" rows="3">{{ $tugas->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kuota Tim (1-10 orang)</label>
                        <input type="number" name="kuota_tim" class="form-control form-control-sm" min="1" max="10" required value="{{ $tugas->kuota_tim }}">
                        <small class="text-body-secondary">Petugas terdaftar: {{ $tugas->petugas_terdaftar }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Tugas</label>
                        <input type="datetime-local" name="tanggal_tugas" class="form-control form-control-sm" required value="{{ $tugas->tanggal_tugas->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status</label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="tersedia" {{ $tugas->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="penuh" {{ $tugas->status == 'penuh' ? 'selected' : '' }}>Penuh</option>
                            <option value="selesai" {{ $tugas->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-coreui-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" style="background-color: #1b5e3a; border-color: #1b5e3a;">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
