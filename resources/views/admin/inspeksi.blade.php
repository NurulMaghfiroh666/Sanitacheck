@extends('layouts.admin')

@section('title', 'Inspeksi Sanitasi')

@section('content')
<div class="mb-2 d-flex justify-content-between align-items-center border-bottom pb-1">
    <div>
        <h5 class="fw-bold mb-0" style="color: #1b5e3a;">Inspeksi Sanitasi</h5>
        <p class="text-body-secondary mb-0" style="font-size: 0.75rem;">Data ITSK RS dr. Soepraoen Kesdam V/BRW Malang</p>
    </div>
    <div class="d-flex gap-1">
        <a href="{{ url('/riwayat-tindak-lanjut') }}" class="btn btn-outline-success fw-bold btn-sm shadow-sm px-2 d-flex align-items-center" style="border-radius: 4px; font-size: 0.75rem;">
            <svg class="icon me-1" style="width: 12px; height: 12px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M494 198.671a40.54 40.54 0 0 0-32.174-27.592l-115.909-18.837-53.732-104.414a40.7 40.7 0 0 0-72.37 0l-53.732 104.414-115.907 18.837a40.7 40.7 0 0 0-22.364 68.827l82.7 83.368-17.9 116.055a40.672 40.672 0 0 0 58.548 42.538L256 428.977l104.843 52.89a40.69 40.69 0 0 0 58.548-42.538l-17.9-116.055 82.7-83.368A40.54 40.54 0 0 0 494 198.671m-32.53 18.7L367.4 312.2l20.364 132.01a8.671 8.671 0 0 1-12.509 9.088L256 393.136 136.744 453.3a8.671 8.671 0 0 1-12.509-9.088L144.6 312.2l-94.069-94.83a8.7 8.7 0 0 1 4.778-14.706l131.841-21.426 61.119-118.767a8.694 8.694 0 0 1 15.462 0l61.119 118.767 131.841 21.426a8.7 8.7 0 0 1 4.778 14.706Z"/></svg>
            Riwayat
        </a>
        <button type="button" class="btn btn-primary text-white fw-bold btn-sm shadow-sm px-2 d-flex align-items-center" data-coreui-toggle="modal" data-coreui-target="#addJadwalModal" style="border-radius: 4px; font-size: 0.75rem;">
            + Jadwal
        </button>
        <button type="button" class="btn btn-success text-white fw-bold btn-sm shadow-sm px-2 d-flex align-items-center" data-coreui-toggle="modal" data-coreui-target="#addTugasModal" style="border-radius: 4px; background-color: #1b5e3a; border-color: #1b5e3a; font-size: 0.75rem;">
            + Tugas
        </button>
    </div>
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

<form action="{{ request()->url() }}" method="GET" class="row gx-2 gy-1 mb-2 align-items-center">
    <div class="col-md-5">
        <div class="input-group input-group-sm shadow-sm border" style="border-radius: 6px; overflow: hidden;">
            <input type="text" name="search" class="form-control border-0 bg-body text-body" placeholder="Cari petugas, lokasi..." value="{{ request('search') }}" style="font-size: 0.8rem;">
            <button type="submit" class="input-group-text bg-body border-0 text-body-secondary px-2">
                <svg style="width: 12px; height: 12px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.72 113.6-13.67 10.33 85.08 20.35 204.3c8.11 96.53 87.57 173.3 184.2 178.6 44.59 2.45 86.82-10.42 121.7-33.82l119.5 119.5c15.6 15.6 40.9 15.6 56.5 0l-1.95-24.9zM224 336c-70.7 0-128-57.3-128-128s57.3-128 128-128 128 57.3 128 128-57.3 128-128 128z"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="col-md-7 d-flex gap-1 justify-content-md-end justify-content-start">
        <select name="status" class="form-select form-select-sm shadow-sm bg-body border" style="border-radius: 6px; width: 110px; font-size: 0.8rem;" onchange="this.form.submit()">
            <option value="">Semua</option>
            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="Dalam Proses" {{ request('status') == 'Dalam Proses' ? 'selected' : '' }}>Proses</option>
            <option value="Tertunda" {{ request('status') == 'Tertunda' ? 'selected' : '' }}>Tertunda</option>
        </select>
        @if(request('search') || request('status'))
            <a href="{{ request()->url() }}" class="btn btn-sm btn-secondary d-flex align-items-center px-2" style="border-radius: 6px; font-size: 0.75rem;">Reset</a>
        @endif
    </div>
</form>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border shadow-sm adaptive-card mb-0" style="border-radius: 10px;">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-1 px-3">
                <h6 class="mb-0 fw-bold text-success" style="font-size: 0.8rem;"><i class="bi bi-list-task me-1"></i>Daftar Tugas Inspeksi</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless table-hover align-middle mb-0 current-theme-table" style="font-size: 0.75rem;">
                        <thead style="border-bottom: 1px solid var(--cui-border-color); background-color: var(--cui-tertiary-bg);">
                            <tr>
                                <th class="text-center text-success py-1 px-2" style="width: 40px;">No</th>
                                <th class="text-success py-1">Tugas & Deskripsi</th>
                                <th class="text-success py-1" style="width: 150px;">Jadwal</th>
                                <th class="text-center text-success py-1" style="width: 70px;">Kuota</th>
                                <th class="text-center text-success py-1" style="width: 80px;">Status</th>
                                <th class="text-center text-success py-1 px-2" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tugasInspeksi as $index => $tugas)
                                <tr>
                                    <td class="text-center py-1 px-2 text-body-secondary">
                                        {{ ($tugasInspeksi->currentPage() - 1) * $tugasInspeksi->perPage() + $index + 1 }}
                                    </td>
                                    <td class="py-1">
                                        <div class="fw-bold text-truncate" style="max-width: 200px;" title="{{ $tugas->judul }}">{{ $tugas->judul }}</div>
                                        <div class="text-body-secondary text-truncate" style="max-width: 200px; font-size: 0.7rem;">{{ $tugas->fasilitas->nama_fasilitas ?? '-' }}</div>
                                    </td>
                                    <td class="py-1 text-body-secondary">
                                        {{ $tugas->tanggal_tugas->format('d M y H:i') }}
                                    </td>
                                    <td class="text-center py-1">
                                        <span class="badge bg-info bg-opacity-10 text-info fw-normal" style="font-size: 0.7rem;">
                                            {{ $tugas->petugas_terdaftar }}/{{ $tugas->kuota_tim }}
                                        </span>
                                    </td>
                                    <td class="text-center py-1">
                                        @if($tugas->status == 'tersedia')
                                            <span class="badge bg-success bg-opacity-10 text-success fw-normal" style="font-size: 0.7rem;">Tersedia</span>
                                        @elseif($tugas->status == 'penuh')
                                            <span class="badge bg-warning bg-opacity-10 text-warning fw-normal" style="font-size: 0.7rem;">Penuh</span>
                                        @elseif($tugas->status == 'selesai')
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal" style="font-size: 0.7rem;">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center py-1 px-2">
                                        <div class="btn-group btn-group-sm shadow-sm" role="group">
                                            <button type="button" class="btn btn-light border p-1" data-coreui-toggle="modal" data-coreui-target="#detailTugasModal{{ $tugas->id }}" title="Detail">
                                                <svg style="width: 12px; height: 12px; color: #0dcaf0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 120C114.6 120 16 237.3 16 256s98.6 136 240 136 240-117.3 240-136-98.6-136-240-136zm0 224c-48.6 0-88-39.4-88-88s39.4-88 88-88 88 39.4 88 88-39.4 88-88 88z"/><circle fill="currentColor" cx="256" cy="256" r="48"/></svg>
                                            </button>
                                            <button type="button" class="btn btn-light border p-1" data-coreui-toggle="modal" data-coreui-target="#editTugasModal{{ $tugas->id }}" title="Edit">
                                                <svg style="width: 12px; height: 12px; color: #0d6efd;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 5.4 506.5s12.5 12.3 21.2 12.3h120.3c10.9 0 21.4-4.3 29.2-12l233.4-233.4L291.7 90.3z"/></svg>
                                            </button>
                                            <button type="button" class="btn btn-light border p-1" title="Hapus" onclick="confirmDelete('{{ route('tugas-inspeksi.destroy', $tugas->id) }}')">
                                                <svg style="width: 12px; height: 12px; color: #dc3545;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.4 467c1.6 25.3 22.6 45 47.9 45H346.7c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-body-secondary">
                                        Belum ada data tugas inspeksi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-transparent border-top py-1">
                    <div style="transform: scale(0.85); transform-origin: left; margin: -5px 0;">
                        {{ $tugasInspeksi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border shadow-sm adaptive-card mb-0" style="border-radius: 10px;">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-1 px-3">
                <h6 class="mb-0 fw-bold text-primary" style="font-size: 0.8rem;"><i class="bi bi-calendar-event me-1"></i>Jadwal Terkini</h6>
            </div>
            <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 current-theme-table" style="font-size: 0.75rem;">
                        <thead style="background-color: var(--cui-tertiary-bg); position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th class="py-1 px-2 text-primary border-bottom">Petugas & Fasilitas</th>
                                <th class="text-center py-1 text-primary border-bottom" style="width: 60px;">Sts</th>
                                <th class="text-center py-1 px-2 text-primary border-bottom" style="width: 50px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwals as $jadwal)
                            <tr>
                                <td class="px-2 py-1">
                                    <div class="fw-bold text-truncate" style="max-width: 150px;">{{ $jadwal->petugas->nama_petugas ?? '-' }}</div>
                                    <div class="d-flex justify-content-between text-body-secondary" style="font-size: 0.65rem;">
                                        <span class="text-truncate" style="max-width: 90px;">{{ $jadwal->fasilitas->nama_fasilitas ?? '-' }}</span>
                                        <span>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/y') }}</span>
                                    </div>
                                </td>
                                <td class="text-center py-1 align-middle">
                                    @if($jadwal->status == 'Selesai')
                                        <span class="badge bg-success-subtle text-success" style="font-size: 0.65rem; padding: 0.2rem 0.3rem;">Sel</span>
                                    @elseif($jadwal->status == 'Dibatalkan')
                                        <span class="badge bg-danger-subtle text-danger" style="font-size: 0.65rem; padding: 0.2rem 0.3rem;">Btl</span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary" style="font-size: 0.65rem; padding: 0.2rem 0.3rem;">Ttd</span>
                                    @endif
                                </td>
                                <td class="text-center py-1 px-2 align-middle">
                                    <button type="button" class="btn btn-sm btn-light border p-1" data-coreui-toggle="modal" data-coreui-target="#detailJadwalModal{{ $jadwal->id }}" title="Detail & Kelola">
                                        <svg style="width: 12px; height: 12px; color: #0dcaf0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 120C114.6 120 16 237.3 16 256s98.6 136 240 136 240-117.3 240-136-98.6-136-240-136zm0 224c-48.6 0-88-39.4-88-88s39.4-88 88-88 88 39.4 88 88-39.4 88-88 88z"/><circle fill="currentColor" cx="256" cy="256" r="48"/></svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Modal Detail Jadwal & Ubah Status -->
                            <div class="modal fade" id="detailJadwalModal{{ $jadwal->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.18);">
                                        <div class="modal-header border-bottom pb-2" style="background: linear-gradient(135deg, #1b5e3a 0%, #2d8f5e 100%); border-radius: 14px 14px 0 0;">
                                            <h6 class="modal-title fw-bold text-white d-flex align-items-center">
                                                <svg class="me-2" style="width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M480 128a64 64 0 0 0-64-64h-16V48h-32v16H144V48h-32v16H96a64 64 0 0 0-64 64v12a4 4 0 0 0 4 4h440a4 4 0 0 0 4-4zm0 36H32v220a64 64 0 0 0 64 64h320a64 64 0 0 0 64-64zm-212 196H176v-32h92zm64-80H176v-32h156z"/></svg>
                                                Detail Jadwal Petugas
                                            </h6>
                                            <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-4 pt-3 pb-2">
                                            <!-- Status Badge -->
                                            <div class="text-center mb-3">
                                                @if($jadwal->status == 'Selesai')
                                                    <span class="badge bg-success px-3 py-2" style="font-size: 0.8rem; border-radius: 20px;">✓ Selesai</span>
                                                @elseif($jadwal->status == 'Berlangsung')
                                                    <span class="badge bg-primary px-3 py-2" style="font-size: 0.8rem; border-radius: 20px;">● Berlangsung</span>
                                                @elseif($jadwal->status == 'Dibatalkan')
                                                    <span class="badge bg-danger px-3 py-2" style="font-size: 0.8rem; border-radius: 20px;">✕ Dibatalkan</span>
                                                @else
                                                    <span class="badge bg-secondary px-3 py-2" style="font-size: 0.8rem; border-radius: 20px;">◷ Menunggu</span>
                                                @endif
                                            </div>

                                            <!-- Info Grid -->
                                            <div class="rounded-3 border p-3 mb-3" style="background-color: var(--cui-tertiary-bg, #f8f9fa);">
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <div class="text-body-secondary" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Petugas</div>
                                                        <div class="fw-bold text-body" style="font-size: 0.85rem;">{{ $jadwal->petugas->nama_petugas ?? '-' }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-body-secondary" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Jenis Tugas</div>
                                                        <div class="fw-bold text-body" style="font-size: 0.85rem;">{{ $jadwal->jenis_tugas }}</div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="text-body-secondary" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Fasilitas</div>
                                                        <div class="fw-bold text-body" style="font-size: 0.85rem;">{{ $jadwal->fasilitas->nama_fasilitas ?? '-' }}</div>
                                                        <div class="text-body-secondary" style="font-size: 0.75rem;">{{ $jadwal->fasilitas->lokasi ?? '-' }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-body-secondary" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Mulai</div>
                                                        <div class="fw-semibold text-body" style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-body-secondary" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Selesai</div>
                                                        <div class="fw-semibold text-body" style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}</div>
                                                    </div>
                                                    @if($jadwal->catatan)
                                                    <div class="col-12">
                                                        <div class="text-body-secondary" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Catatan</div>
                                                        <div class="text-body" style="font-size: 0.85rem;">{{ $jadwal->catatan }}</div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Foto Inspeksi -->
                                            <div class="mb-3">
                                                <h6 class="fw-bold mb-2 text-body" style="font-size: 0.8rem;">Bukti Foto Inspeksi</h6>
                                                @if($jadwal->photos->isEmpty())
                                                    <div class="text-center py-3 bg-light rounded" style="border-radius: 8px;">
                                                        <i class="bi bi-images text-secondary" style="font-size: 1.5rem;"></i>
                                                        <p class="text-muted mt-1 mb-0" style="font-size: 0.75rem;">Belum ada foto.</p>
                                                    </div>
                                                @else
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($jadwal->photos as $photo)
                                                            <div class="position-relative" style="width: 80px; height: 80px; cursor: pointer;" onclick="openPreview('{{ asset('storage/' . $photo->foto_path) }}')">
                                                                <img src="{{ asset('storage/' . $photo->foto_path) }}" alt="Foto" class="img-fluid rounded shadow-sm" style="width: 100%; height: 100%; object-fit: cover;">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Admin: Ubah Status -->
                                            <div class="border-top pt-3">
                                                <h6 class="fw-bold mb-2 text-body" style="font-size: 0.8rem;">
                                                    <svg class="me-1" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M262.29 192.31a64 64 0 1 0 57.4 57.4 64.13 64.13 0 0 0-57.4-57.4zM416.39 256a154.34 154.34 0 0 1-1.53 20.79l45.21 35.46a10.81 10.81 0 0 1 2.45 13.75l-42.77 74a10.81 10.81 0 0 1-13.14 4.59l-44.9-18.08a16.11 16.11 0 0 0-15.17 1.75A164.48 164.48 0 0 1 325 400.8a15.94 15.94 0 0 0-8.82 12.14l-6.73 47.89a11.08 11.08 0 0 1-10.68 9.17h-85.54a11.11 11.11 0 0 1-10.69-8.87l-6.72-47.82a16.07 16.07 0 0 0-9-12.22 155.3 155.3 0 0 1-21.46-12.57 16.07 16.07 0 0 0-15.11-1.71l-44.89 18.07a10.81 10.81 0 0 1-13.14-4.58l-42.77-74a10.8 10.8 0 0 1 2.45-13.75l38.21-30a16.05 16.05 0 0 0 6-14.08c-.36-4.17-.58-8.33-.58-12.5s.21-8.27.58-12.35a16 16 0 0 0-6.07-13.94l-38.19-30A10.81 10.81 0 0 1 49.48 186l42.77-74a10.81 10.81 0 0 1 13.14-4.59l44.9 18.08a16.11 16.11 0 0 0 15.17-1.75 164.48 164.48 0 0 1 21.55-12.54 15.94 15.94 0 0 0 8.82-12.14l6.73-47.89A11.08 11.08 0 0 1 213.24 42h85.54a11.11 11.11 0 0 1 10.69 8.87l6.72 47.82a16.07 16.07 0 0 0 9 12.22 155.3 155.3 0 0 1 21.46 12.57 16.07 16.07 0 0 0 15.11 1.71l44.89-18.07a10.81 10.81 0 0 1 13.14 4.58l42.77 74a10.8 10.8 0 0 1-2.45 13.75l-38.21 30a16.05 16.05 0 0 0-6.05 14.08c.33 4.14.55 8.3.55 12.47z"/></svg>
                                                    Ubah Status Jadwal
                                                </h6>
                                                <form action="{{ route('jadwal-petugas.updateStatus', $jadwal->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-select form-select-sm" style="border-radius: 6px; font-size: 0.8rem;">
                                                        <option value="Menunggu" {{ $jadwal->status == 'Menunggu' ? 'selected' : '' }}>◷ Menunggu</option>
                                                        <option value="Berlangsung" {{ $jadwal->status == 'Berlangsung' ? 'selected' : '' }}>● Berlangsung</option>
                                                        <option value="Selesai" {{ $jadwal->status == 'Selesai' ? 'selected' : '' }}>✓ Selesai</option>
                                                        <option value="Dibatalkan" {{ $jadwal->status == 'Dibatalkan' ? 'selected' : '' }}>✕ Dibatalkan</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-primary fw-bold text-white text-nowrap" style="border-radius: 6px; font-size: 0.8rem; padding: 0.35rem 1rem;">
                                                        Simpan
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0 px-4 pb-3">
                                            <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-semibold" data-coreui-dismiss="modal" style="border-radius: 8px;">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted" style="font-size: 0.75rem;">Belum ada jadwal.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-2">
            <div class="mb-0 shadow-sm custom-pagination" style="transform: scale(0.8); transform-origin: top;">
                {{ $jadwals->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Buat Jadwal Petugas -->
<div class="modal fade" id="addJadwalModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title text-primary fw-bold">Buat Jadwal Inspeksi untuk Petugas</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('jadwal-petugas.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold text-primary mb-1">Pilih Petugas</label>
                <select name="petugas_id" class="form-select form-select-sm bg-adaptive-input" required style="border-radius: 6px;">
                    <option selected disabled value="">-- Pilih Petugas --</option>
                    @foreach($petugasList ?? [] as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_petugas }} ({{ $p->email ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-primary mb-1">Pilih Fasilitas</label>
                <select name="fasilitas_id" class="form-select form-select-sm bg-adaptive-input" required style="border-radius: 6px;">
                    <option selected disabled value="">-- Pilih Fasilitas --</option>
                    @foreach($fasilitasList ?? [] as $f)
                        <option value="{{ $f->id }}">{{ $f->nama_fasilitas }} ({{ $f->lokasi }})</option>
                    @endforeach
                </select>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-primary mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm bg-adaptive-input" required style="border-radius: 6px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-primary mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control form-control-sm bg-adaptive-input" required style="border-radius: 6px;">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-primary mb-1">Jenis Tugas</label>
                <select name="jenis_tugas" class="form-select form-select-sm bg-adaptive-input" required style="border-radius: 6px;">
                    <option value="Inspeksi Sanitasi">Inspeksi Sanitasi</option>
                    <option value="Pembersihan Rutin">Pembersihan Rutin</option>
                    <option value="Pemeliharaan">Pemeliharaan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-primary mb-1">Catatan (Opsional)</label>
                <textarea name="catatan" class="form-control form-control-sm bg-adaptive-input" rows="2" placeholder="Petunjuk tambahan untuk petugas..."></textarea>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-0 pb-0">
                <button type="button" class="btn btn-outline-secondary btn-sm fw-bold" data-coreui-dismiss="modal" style="border-radius: 6px; padding: 0.4rem 1rem;">Batal</button>
                <button type="submit" class="btn btn-primary text-white btn-sm fw-bold shadow-sm" style="border-radius: 6px; padding: 0.4rem 1rem;">Buat Jadwal</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Tambahan CSS Khusus Tema & Ukuran Minimalis -->
<style>
    .extra-small {
        font-size: 0.65rem !important;
    }

    /* Default Mode Terang (untuk form inputs) */
    .input-group .form-control::placeholder {
        color: #adb5bd;
    }

    /* Aturan Mode Gelap CoreUI */
    html[data-coreui-theme="dark"] .bg-body {
        background-color: var(--cui-body-bg) !important;
    }
    
    html[data-coreui-theme="dark"] .bg-white {
        background-color: var(--cui-body-bg) !important;
    }

    html[data-coreui-theme="dark"] .adaptive-card {
        background-color: var(--cui-card-bg) !important;
        border-color: var(--cui-border-color) !important;
    }

    html[data-coreui-theme="dark"] .current-theme-table thead tr th {
        color: var(--cui-tertiary-color) !important;
    }

    /* Bootstrap 5 Adaptive Badge */
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.2);
    }
    .text-success {
        color: #198754 !important;
    }

    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.2);
    }
    .text-warning {
        color: #ffc107 !important;
    }

    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.2);
    }
    .text-danger {
        color: #dc3545 !important;
    }
</style>

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
@foreach($tugasInspeksi ?? [] as $tugas)
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

<!-- Modal Detail Tugas -->
<div class="modal fade" id="detailTugasModal{{ $tugas->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-success"><i class="bi bi-info-circle me-2"></i>Detail Tugas Inspeksi</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Judul Tugas</div>
                        <div class="fw-bold fs-6">{{ $tugas->judul }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Fasilitas & Lokasi</div>
                        <div class="fw-bold">{{ $tugas->fasilitas->nama_fasilitas ?? '-' }}</div>
                        <div class="text-muted small">{{ $tugas->fasilitas->lokasi ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-body-secondary small text-uppercase">Tanggal Tugas</div>
                        <div class="fw-semibold">{{ $tugas->tanggal_tugas->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-body-secondary small text-uppercase">Status</div>
                        <div>
                            @if($tugas->status == 'tersedia')
                                <span class="badge bg-success bg-opacity-10 text-success">Tersedia</span>
                            @elseif($tugas->status == 'penuh')
                                <span class="badge bg-warning bg-opacity-10 text-warning">Penuh</span>
                            @elseif($tugas->status == 'selesai')
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Selesai</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-body-secondary small text-uppercase">Deskripsi</div>
                        <div class="p-2 bg-light rounded small">{{ $tugas->deskripsi ?: 'Tidak ada deskripsi' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-body-secondary small text-uppercase">Tim Petugas ({{ $tugas->petugas_terdaftar }}/{{ $tugas->kuota_tim }})</div>
                        </div>
                        <ul class="list-group list-group-flush border rounded">
                            @forelse($tugas->users as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                <div>
                                    <div class="fw-semibold small">{{ $user->petugas->nama_petugas ?? $user->name }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Bergabung: {{ \Carbon\Carbon::parse($user->pivot->joined_at)->format('d M Y H:i') }}</div>
                                </div>
                            </li>
                            @empty
                            <li class="list-group-item text-center text-muted small py-3">Belum ada petugas yang bergabung</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm w-100" data-coreui-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Preview Foto (Lightbox) -->
<div class="modal fade" id="modalPreviewFoto" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
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
        var modal = new coreui.Modal(document.getElementById('modalPreviewFoto'));
        modal.show();
    }
</script>
@endpush