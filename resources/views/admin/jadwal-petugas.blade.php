@extends('layouts.admin')

@section('title', 'Jadwal Inspeksi Petugas')

@section('content')
<div class="mb-3">
    <div class="d-flex align-items-center gap-2 mb-1">
        <h3 class="fw-bold mb-0" style="color: #1b5e3a;">Jadwal Inspeksi Petugas</h3>
    </div>
    <div class="d-flex align-items-center text-body-secondary small">
        <span>Dashboard</span>
        <span class="mx-2">/</span>
        <span>Jadwal Inspeksi Petugas</span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-success text-white me-3 d-flex justify-content-center align-items-center shadow-sm" style="border-radius: 8px; width: 48px; height: 48px; background-color: #28a745 !important;">
                    <svg class="icon" style="width: 24px; height: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M136.262 250.707a123.635 123.635 0 1 0-82.524 0 178.293 178.293 0 0 0-35.122 36.46 16 16 0 0 0 25.86 18.04A144.3 144.3 0 0 1 93 277.207v186.2a16 16 0 0 0 16 16h4a16 16 0 0 0 16-16v-87.41h10v87.41a16 16 0 0 0 16 16h4a16 16 0 0 0 16-16V277.207a144.3 144.3 0 0 1 48.538 28 16 16 0 0 0 25.86-18.04 178.293 178.293 0 0 0-35.122-36.46zM135 139.635a59.635 59.635 0 1 1-59.635 59.635A59.7 59.7 0 0 1 135 139.635zM375 250.707a123.635 123.635 0 1 0-82.524 0 178.293 178.293 0 0 0-35.122 36.46 16 16 0 0 0 25.86 18.04 144.3 144.3 0 0 1 48.538-28v186.2a16 16 0 0 0 16 16h4a16 16 0 0 0 16-16v-87.41h10v87.41a16 16 0 0 0 16 16h4a16 16 0 0 0 16-16V277.207a144.3 144.3 0 0 1 48.538 28 16 16 0 0 0 25.86-18.04 178.293 178.293 0 0 0-35.122-36.46zM375 139.635a59.635 59.635 0 1 1-59.635 59.635A59.7 59.7 0 0 1 375 139.635z"/>
                    </svg>
                </div>
                <div>
                    <div class="fw-bold" style="font-size: 0.8rem; color: #333;">Total Petugas</div>
                    <div class="fw-bold" style="font-size: 1.5rem; line-height: 1; color: #333;">{{ $totalPetugas }}</div>
                    <div class="text-body-secondary" style="font-size: 0.7rem;">Semua petugas terdaftar</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-primary text-white me-3 d-flex justify-content-center align-items-center shadow-sm" style="border-radius: 8px; width: 48px; height: 48px; background-color: #0d6efd !important;">
                    <svg class="icon" style="width: 24px; height: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M336 256c-52.938 0-96-43.063-96-96s43.062-96 96-96 96 43.063 96 96-43.062 96-96 96m138.188-9.376c27.57-22.046 45.812-55.828 45.812-93.811C520 68.343 449.656-2 365.188-2 323.96-2 286.555 14.484 259.188 41.094c-19.39 18.78-32.844 43.39-37.375 70.906H192C85.961 112 0 197.961 0 304v160a16 16 0 0 0 16 16h480a16 16 0 0 0 16-16V304c0-38.875-11.688-75.063-31.812-105.376"/>
                    </svg>
                </div>
                <div>
                    <div class="fw-bold" style="font-size: 0.8rem; color: #333;">Petugas Aktif</div>
                    <div class="fw-bold" style="font-size: 1.5rem; line-height: 1; color: #333;">{{ $aktifPetugas }}</div>
                    <div class="text-body-secondary" style="font-size: 0.7rem;">Sedang bertugas</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-warning text-white me-3 d-flex justify-content-center align-items-center shadow-sm" style="border-radius: 8px; width: 48px; height: 48px; background-color: #ffc107 !important;">
                    <svg class="icon" style="width: 24px; height: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M400 464H48V104h192V72H16v424h416V272h-32z"/>
                        <path fill="currentColor" d="M304 16v32h137.373L188.687 300.687l22.626 22.626L464 70.627V208h32V16z"/>
                    </svg>
                </div>
                <div>
                    <div class="fw-bold" style="font-size: 0.8rem; color: #333;">Jadwal Hari Ini</div>
                    <div class="fw-bold" style="font-size: 1.5rem; line-height: 1; color: #333;">{{ $jadwalHariIni }}</div>
                    <div class="text-body-secondary" style="font-size: 0.7rem;">Petugas dijadwalkan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card border shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="text-white me-3 d-flex justify-content-center align-items-center shadow-sm" style="border-radius: 8px; width: 48px; height: 48px; background-color: #6f42c1 !important;">
                    <svg class="icon text-white" style="width: 24px; height: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm-80-224c0-17.67 14.33-32 32-32s32 14.33 32 32-14.33 32-32 32-32-14.33-32-32zm96 0c0-17.67 14.33-32 32-32s32 14.33 32 32-14.33 32-32 32-32-14.33-32-32z"/>
                    </svg>
                </div>
                <div>
                    <div class="fw-bold" style="font-size: 0.8rem; color: #333;">Total Jadwal</div>
                    <div class="fw-bold" style="font-size: 1.5rem; line-height: 1; color: #333;">{{ $totalShift }}</div>
                    <div class="text-body-secondary" style="font-size: 0.7rem;">Seluruh jadwal inspeksi</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Schedule Form -->
<div class="card border shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-4">
        <h5 class="card-title fw-bold mb-3" style="color: #333; font-size: 1rem;">Buat Jadwal Inspeksi Baru</h5>
        <form action="{{ route('jadwal-petugas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label text-body-secondary small fw-semibold mb-1">Petugas</label>
                    <select name="petugas_id" class="form-select shadow-sm" style="border-radius: 6px; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: var(--cui-border-color);" required>
                        <option value="">Pilih Petugas</option>
                        @foreach($petugas as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_petugas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label text-body-secondary small fw-semibold mb-1">Fasilitas</label>
                    <select name="fasilitas_id" class="form-select shadow-sm" style="border-radius: 6px; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: var(--cui-border-color);" required>
                        <option value="">Pilih Fasilitas</option>
                        @foreach($fasilitas as $f)
                        <option value="{{ $f->id }}">{{ $f->nama_fasilitas }} ({{ $f->lokasi }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label text-body-secondary small fw-semibold mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control shadow-sm" style="border-radius: 6px; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: var(--cui-border-color);" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label text-body-secondary small fw-semibold mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control shadow-sm" style="border-radius: 6px; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: var(--cui-border-color);" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label text-body-secondary small fw-semibold mb-1">Jenis Tugas</label>
                    <select name="jenis_tugas" class="form-select shadow-sm" style="border-radius: 6px; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: var(--cui-border-color);" required>
                        <option value="Inspeksi Sanitasi">Inspeksi Sanitasi</option>
                        <option value="Pembersihan Rutin">Pembersihan Rutin</option>
                        <option value="Pemeliharaan">Pemeliharaan</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 mb-3">
                    <label class="form-label text-body-secondary small fw-semibold mb-1">Catatan (Opsional)</label>
                    <input type="text" name="catatan" class="form-control shadow-sm" style="border-radius: 6px; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: var(--cui-border-color);" placeholder="Tambahkan catatan untuk petugas...">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-success text-white fw-semibold shadow-sm w-100" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a; font-size: 0.85rem; padding: 0.5rem 1rem;">
                        + Buat Jadwal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="p-4 border-bottom">
            <h5 class="card-title fw-bold mb-0" style="color: #333; font-size: 1.1rem;">Daftar Jadwal Inspeksi</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0">
                <thead style="border-bottom: 1px solid var(--cui-border-color); background-color: transparent;">
                    <tr>
                        <th class="text-body-secondary py-3 px-3 fw-semibold" style="font-size: 0.8rem; width: 40px;">No</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Petugas</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Fasilitas</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Jenis Tugas</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Periode</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Status</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Foto</th>
                        <th class="text-body-secondary py-3 fw-semibold text-center" style="font-size: 0.8rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $i => $jadwal)
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td class="text-center text-body-secondary small py-3 px-3">{{ $i + 1 }}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2 bg-success text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 50%;">
                                    {{ substr($jadwal->petugas->nama_petugas ?? 'P', 0, 1) }}
                                </div>
                                <div>
                                    <div class="small fw-semibold" style="color: #333; font-size: 0.8rem;">{{ $jadwal->petugas->nama_petugas ?? '-' }}</div>
                                    <div class="text-body-secondary" style="font-size: 0.7rem;">Petugas</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="small" style="color: #333; font-size: 0.8rem;">{{ $jadwal->fasilitas->nama_fasilitas ?? '-' }}</div>
                            <div class="text-body-secondary" style="font-size: 0.7rem;">({{ $jadwal->fasilitas->lokasi ?? '-' }})</div>
                        </td>
                        <td class="py-3">
                            <div class="small fw-semibold" style="color: #333; font-size: 0.8rem;">{{ $jadwal->jenis_tugas }}</div>
                        </td>
                        <td class="py-3">
                            <div class="small" style="color: #333; font-size: 0.8rem;">{{ $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('d/m/Y') : '-' }}</div>
                            <div class="text-body-secondary" style="font-size: 0.7rem;">s/d {{ $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('d/m/Y') : '-' }}</div>
                        </td>
                        <td class="py-3">
                            @if($jadwal->status == 'Menunggu')
                            <span class="badge bg-secondary" style="font-size: 0.7rem;">Menunggu</span>
                            @elseif($jadwal->status == 'Berlangsung')
                            <span class="badge bg-primary" style="font-size: 0.7rem;">Berlangsung</span>
                            @elseif($jadwal->status == 'Selesai')
                            <span class="badge bg-success" style="font-size: 0.7rem;">Selesai</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="small" style="color: #333; font-size: 0.8rem;">{{ $jadwal->photos->count() }} foto</div>
                        </td>
                        <td class="py-3 text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('jadwal-petugas.progress', $jadwal->id) }}" class="btn btn-sm btn-light" style="border-radius: 6px; padding: 0.3rem 0.6rem;" title="Lihat Progres">
                                    <svg style="width: 16px; height: 16px; color: #0d6efd;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm-80-224c0-17.67 14.33-32 32-32s32 14.33 32 32-14.33 32-32 32-32-14.33-32-32zm96 0c0-17.67 14.33-32 32-32s32 14.33 32 32-14.33 32-32 32-32-14.33-32-32z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('jadwal-petugas.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light" style="border-radius: 6px; padding: 0.3rem 0.6rem;" title="Hapus">
                                        <svg style="width: 16px; height: 16px; color: #dc3545;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path fill="currentColor" d="M112 48v48h288V48H112zm0 416h288V128H112v336zm48-240h192v32H160V224zm0 80h192v32H160V304z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada jadwal inspeksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection