@extends('layouts.admin')

@section('title', 'Fasilitas Umum')

@section('content')
<div class="mb-2 d-flex justify-content-between align-items-center border-bottom pb-1">
    <div>
        <h5 class="fw-bold mb-0" style="color: #1b5e3a;">Fasilitas Umum</h5>
        <div class="text-body-secondary mt-1" style="font-size: 0.75rem;">
            <span>Fasilitas Umum ITSK RS dr. Soepraoen Kesdam V/BRW Malang</span>
        </div>
    </div>
    <button type="button" class="btn btn-success text-white fw-bold shadow-sm px-2 py-1 d-flex align-items-center" data-coreui-toggle="modal" data-coreui-target="#addFasilitasModal" style="border-radius: 4px; background-color: #1b5e3a; border-color: #1b5e3a; font-size: 0.75rem;">
        + Fasilitas
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
    {{ session('success') }}
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

<div class="card mb-2 border shadow-sm" style="border-radius: 8px;">
    <div class="card-body p-2">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <div class="d-flex align-items-center p-1 border rounded bg-adaptive-stat flex-grow-1" style="border-color: var(--cui-border-color, var(--bs-border-color)) !important; border-radius: 6px !important;">
                <div class="bg-success text-white me-2 d-flex justify-content-center align-items-center shadow-sm" style="border-radius: 4px; width: 32px; height: 32px; min-width: 32px;">
                    <svg class="icon" style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M334.627 16H48v480h424V153.373ZM440 166.627V168H320V48h1.373ZM80 464V48h208v152h152v264Z"/><path fill="currentColor" d="M136 296h224v32H136zm0 80h224v32H136z"/></svg>
                </div>
                <div>
                    <div class="text-success fw-bold lh-sm" style="font-size: 0.7rem;">Total Fasilitas</div>
                    <div class="fw-bold text-success" style="font-size: 1.1rem; line-height: 1;">{{ $totalFasilitas }}</div>
                </div>
            </div>
            
            <div class="d-flex align-items-center p-1 border rounded bg-adaptive-stat flex-grow-1" style="border-color: var(--cui-border-color, var(--bs-border-color)) !important; border-radius: 6px !important;">
                <div class="bg-primary text-white me-2 d-flex justify-content-center align-items-center shadow-sm" style="border-radius: 4px; width: 32px; height: 32px; min-width: 32px;">
                    <svg class="icon" style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 48C141.31 48 48 141.31 48 256s93.31 208 208 208 208-93.31 208-208S370.69 48 256 48Zm108.25 138.29-134.4 160a16 16 0 0 1-23.35 1.14l-80-80a16 16 0 0 1 22.62-22.62l67.24 67.24 123.36-146.85a16 16 0 0 1 24.53 21.09Z" /></svg>
                </div>
                <div>
                    <div class="text-success fw-bold lh-sm" style="font-size: 0.7rem;">Aktif</div>
                    <div class="fw-bold text-success" style="font-size: 1.1rem; line-height: 1;">{{ $aktifFasilitas }}</div>
                </div>
            </div>
            
            <div class="d-flex align-items-center p-1 border rounded bg-adaptive-stat flex-grow-1" style="border-color: var(--cui-border-color, var(--bs-border-color)) !important; border-radius: 6px !important;">
                <div class="bg-warning text-white me-2 d-flex justify-content-center align-items-center shadow-sm" style="border-radius: 4px; width: 32px; height: 32px; min-width: 32px;">
                    <svg class="icon" style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 48C141.31 48 48 141.31 48 256s93.31 208 208 208 208-93.31 208-208S370.69 48 256 48Zm-32 272a16 16 0 0 1-32 0V192a16 16 0 0 1 32 0Zm96 0a16 16 0 0 1-32 0V192a16 16 0 0 1 32 0Z" /></svg>
                </div>
                <div>
                    <div class="text-success fw-bold lh-sm" style="font-size: 0.7rem;">Tidak Aktif</div>
                    <div class="fw-bold text-success" style="font-size: 1.1rem; line-height: 1;">{{ $tidakAktifFasilitas }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-2 gap-2">
    <div class="flex-grow-1" style="max-width: 350px;">
        <div class="input-group shadow-sm" style="border-radius: 6px; overflow: hidden;">
            <input type="text" class="form-control border-end-0 bg-adaptive-input text-body" placeholder="Cari fasilitas, lokasi, PJ..." style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
            <span class="input-group-text border-start-0 bg-adaptive-input text-body" style="cursor: pointer; padding: 0.35rem 0.75rem;">
                <svg class="icon text-body-secondary" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.72 113.6-13.67 10.33 85.08 20.35 204.3c8.11 96.53 87.57 173.3 184.2 178.6 44.59 2.45 86.82-10.42 121.7-33.82l119.5 119.5c15.6 15.6 40.9 15.6 56.5 0l-1.95-24.9zM224 336c-70.7 0-128-57.3-128-128s57.3-128 128-128 128 57.3 128 128-57.3 128-128 128z"/>
                </svg>
            </span>
        </div>
    </div>
    
    <div class="d-flex gap-2 align-items-end flex-wrap w-100 w-md-auto justify-content-md-end">
        <div>
            <label class="form-label text-success fw-bold mb-0" style="font-size: 0.7rem;">Jenis Fasilitas</label>
            <select class="form-select shadow-sm bg-adaptive-input text-body" style="border-radius: 6px; min-width: 110px; padding: 0.35rem 1.5rem 0.35rem 0.5rem; font-size: 0.8rem;">
                <option selected>Semua Jenis</option>
                <option value="1">Toilet</option>
                <option value="2">Kantin</option>
                <option value="3">Wastafel</option>
            </select>
        </div>
        <div>
            <label class="form-label text-success fw-bold mb-0" style="font-size: 0.7rem;">Status</label>
            <select class="form-select shadow-sm bg-adaptive-input text-body" style="border-radius: 6px; min-width: 110px; padding: 0.35rem 1.5rem 0.35rem 0.5rem; font-size: 0.8rem;">
                <option selected>Semua Status</option>
                <option value="1">Aktif</option>
                <option value="2">Tidak Aktif</option>
            </select>
        </div>
        <div>
            <button class="btn btn-outline-success d-flex align-items-center fw-bold shadow-sm" style="border-radius: 6px; padding: 0.35rem 0.75rem; border-color: #1b5e3a; color: #1b5e3a; background-color: transparent; font-size: 0.8rem;">
                <svg class="icon me-1" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M256 48A208 208 0 1 0 464 256 208 208 0 0 0 256 48zm0 384A176 176 0 1 1 432 256 176 176 0 0 1 256 432z" />
                    <path fill="currentColor" d="M344.4 167.6a16 16 0 0 0-22.6 0l-91.8 91.8L190.6 220a16 16 0 1 0-22.6 22.6l50.6 50.6a16 16 0 0 0 22.6 0l103.2-103.2a16 16 0 0 0 0-22.4z" />
                </svg>
                RESET
            </button>
        </div>
    </div>
</div>

<div class="card border shadow-sm mb-3" style="border-radius: 8px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-borderless table-hover align-middle mb-0" style="font-size: 0.75rem;">
                <thead style="border-bottom: 1px solid var(--cui-border-color, var(--bs-border-color)); background-color: var(--cui-tertiary-bg, var(--bs-tertiary-bg));">
                    <tr>
                        <th class="text-center text-success py-1 px-2" style="width: 40px;">No</th>
                        <th class="text-success py-1">Fasilitas & Jenis</th>
                        <th class="text-success py-1">Lokasi & PJ</th>
                        <th class="text-center text-success py-1" style="width: 80px;">Status</th>
                        <th class="text-center text-success py-1 px-2" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fasilitas as $index => $item)
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td class="text-center text-body-secondary py-1 px-2">{{ $fasilitas->firstItem() + $index }}</td>
                        <td class="py-1">
                            <div class="fw-bold text-truncate" style="max-width: 150px; color: #333;">{{ $item->nama_fasilitas }}</div>
                            <div class="text-body-secondary text-capitalize text-truncate" style="max-width: 150px; font-size: 0.7rem;">{{ $item->jenis_fasilitas }}</div>
                        </td>
                        <td class="py-1">
                            <div class="fw-semibold text-truncate" style="max-width: 150px; color: #333;">{{ $item->lokasi }}</div>
                            <div class="text-body-secondary text-truncate" style="max-width: 150px; font-size: 0.7rem;"><i class="bi bi-person me-1"></i>{{ $item->penanggung_jawab }}</div>
                        </td>
                        <td class="text-center py-1">
                            @if($item->status_aktif)
                                <span class="badge bg-success bg-opacity-10 text-success fw-normal" style="font-size: 0.7rem;">Aktif</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning fw-normal" style="font-size: 0.7rem;">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="text-center py-1 px-2">
                            <div class="btn-group btn-group-sm shadow-sm" role="group">
                                <button type="button" class="btn btn-light border p-1" data-coreui-toggle="modal" data-coreui-target="#editFasilitasModal{{ $item->id }}" title="Edit">
                                    <svg style="width: 12px; height: 12px; color: #0d6efd;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 5.4 506.5s12.5 12.3 21.2 12.3h120.3c10.9 0 21.4-4.3 29.2-12l233.4-233.4L291.7 90.3z"/></svg>
                                </button>
                                <button type="button" class="btn btn-light border p-1" title="Hapus" onclick="confirmDelete('{{ route('fasilitas.destroy', $item->id) }}')">
                                    <svg style="width: 12px; height: 12px; color: #dc3545;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.4 467c1.6 25.3 22.6 45 47.9 45H346.7c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Modal Edit Fasilitas -->
                    <div class="modal fade" id="editFasilitasModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
                          <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title text-success fw-bold" style="color: #1b5e3a !important;">Edit Fasilitas</h5>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form action="{{ route('fasilitas.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body text-start">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-success mb-1">Nama Fasilitas</label>
                                    <input type="text" name="nama_fasilitas" class="form-control form-control-sm bg-adaptive-input" value="{{ $item->nama_fasilitas }}" required style="border-radius: 6px; padding: 0.4rem 0.75rem;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-success mb-1">Jenis Fasilitas</label>
                                    <select name="jenis_fasilitas" class="form-select form-select-sm bg-adaptive-input" required style="border-radius: 6px; padding: 0.4rem 2rem 0.4rem 0.75rem;">
                                        <option value="toilet" {{ $item->jenis_fasilitas == 'toilet' ? 'selected' : '' }}>Toilet</option>
                                        <option value="kantin" {{ $item->jenis_fasilitas == 'kantin' ? 'selected' : '' }}>Kantin</option>
                                        <option value="ruang tunggu" {{ $item->jenis_fasilitas == 'ruang tunggu' ? 'selected' : '' }}>Ruang Tunggu</option>
                                        <option value="tempat cuci tangan" {{ $item->jenis_fasilitas == 'tempat cuci tangan' ? 'selected' : '' }}>Tempat Cuci Tangan</option>
                                        <option value="ruang kelas" {{ $item->jenis_fasilitas == 'ruang kelas' ? 'selected' : '' }}>Ruang Kelas</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-success mb-1">Lokasi</label>
                                    <input type="text" name="lokasi" class="form-control form-control-sm bg-adaptive-input" value="{{ $item->lokasi }}" required style="border-radius: 6px; padding: 0.4rem 0.75rem;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-success mb-1">Penanggung Jawab</label>
                                    <input type="text" name="penanggung_jawab" class="form-control form-control-sm bg-adaptive-input" value="{{ $item->penanggung_jawab }}" required style="border-radius: 6px; padding: 0.4rem 0.75rem;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-success mb-1">Status</label>
                                    <select name="status_aktif" class="form-select form-select-sm bg-adaptive-input" required style="border-radius: 6px; padding: 0.4rem 2rem 0.4rem 0.75rem;">
                                        <option value="1" {{ $item->status_aktif ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ !$item->status_aktif ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer border-top-0 pt-0">
                                <button type="button" class="btn btn-outline-secondary btn-sm fw-bold" data-coreui-dismiss="modal" style="border-radius: 6px; padding: 0.4rem 1rem;">Batal</button>
                                <button type="submit" class="btn btn-success text-white btn-sm fw-bold shadow-sm" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a; padding: 0.4rem 1rem;">Simpan Perubahan</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                <p class="text-body-secondary mb-0" style="font-size: 0.85rem;">Belum ada data yang di tambahkan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-2">
    <div class="text-body-secondary" style="font-size: 0.7rem;">Menampilkan {{ $fasilitas->firstItem() ?? 0 }} sampai {{ $fasilitas->lastItem() ?? 0 }} dari {{ $fasilitas->total() }} Data</div>
    <div class="mb-0 shadow-sm custom-pagination">
        {{ $fasilitas->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .custom-pagination .pagination {
        margin-bottom: 0;
        --bs-pagination-padding-x: 0.5rem;
        --bs-pagination-padding-y: 0.25rem;
        --bs-pagination-font-size: 0.875rem;
    }
</style>

<!-- Modal Tambah Fasilitas / Jadwal -->
<div class="modal fade" id="addFasilitasModal" tabindex="-1" aria-labelledby="addFasilitasModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title text-success fw-bold" id="addFasilitasModalLabel" style="color: #1b5e3a !important;">Tambah Fasilitas</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formTambahFasilitas" action="{{ route('fasilitas.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold text-success mb-1">Nama Fasilitas</label>
                <input type="text" name="nama_fasilitas" class="form-control form-control-sm bg-adaptive-input" placeholder="Contoh: Toilet Lantai 1" required style="border-radius: 6px; padding: 0.4rem 0.75rem;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-success mb-1">Jenis Fasilitas</label>
                <select name="jenis_fasilitas" class="form-select form-select-sm bg-adaptive-input" required style="border-radius: 6px; padding: 0.4rem 2rem 0.4rem 0.75rem;">
                    <option selected disabled value="">Pilih Jenis</option>
                    <option value="toilet">Toilet</option>
                    <option value="kantin">Kantin</option>
                    <option value="ruang tunggu">Ruang Tunggu</option>
                    <option value="tempat cuci tangan">Tempat Cuci Tangan</option>
                    <option value="ruang kelas">Ruang Kelas</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-success mb-1">Lokasi</label>
                <input type="text" name="lokasi" class="form-control form-control-sm bg-adaptive-input" placeholder="Contoh: Gedung A" required style="border-radius: 6px; padding: 0.4rem 0.75rem;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-success mb-1">Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab" class="form-control form-control-sm bg-adaptive-input" placeholder="Nama Petugas" required style="border-radius: 6px; padding: 0.4rem 0.75rem;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-success mb-1">Status</label>
                <select name="status_aktif" class="form-select form-select-sm bg-adaptive-input" required style="border-radius: 6px; padding: 0.4rem 2rem 0.4rem 0.75rem;">
                    <option value="1" selected>Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
      </div>
      <div class="modal-footer border-top-0 pt-0">
        <button type="button" class="btn btn-outline-secondary btn-sm fw-bold" data-coreui-dismiss="modal" style="border-radius: 6px; padding: 0.4rem 1rem;">Batal</button>
        <button type="submit" class="btn btn-success text-white btn-sm fw-bold shadow-sm" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a; padding: 0.4rem 1rem;">Simpan Data</button>
      </div>
        </form>
    </div>
  </div>
</div>

<style>
    .py-1d5 {
        padding-top: 0.35rem !important;
        padding-bottom: 0.35rem !important;
    }
    .p-1d5 {
        padding: 0.35rem !important;
    }
    .extra-small {
        font-size: 0.65rem !important;
    }
    
    /* Adaptif Latar Belakang Komponen Statistik */
    .bg-adaptive-stat {
        background-color: var(--cui-card-bg, var(--bs-card-bg, transparent)) !important;
    }

    /* Adaptif Latar Belakang Input / Select Halaman */
    .bg-adaptive-input {
        background-color: var(--cui-body-bg, var(--bs-body-bg, #ffffff)) !important;
        border-color: var(--cui-border-color, var(--bs-border-color)) !important;
    }
    
    /* Force teks pencarian mengikuti tema aktif */
    .bg-adaptive-input::placeholder {
        color: var(--cui-body-color, var(--bs-body-color)) !important;
        opacity: 0.6;
    }
</style>
@endsection