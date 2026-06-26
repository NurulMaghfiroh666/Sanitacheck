@extends('layouts.admin')

@section('title', 'Kelola Petugas')

@section('content')
<div class="mb-3">
    <div class="d-flex align-items-center gap-2 mb-1">
        <h3 class="fw-bold mb-0" style="color: #1b5e3a;">Kelola Petugas</h3>
    </div>
    <div class="d-flex align-items-center text-body-secondary small">
        <span>Dashboard</span>
        <span class="mx-2">/</span>
        <span>Kelola Petugas</span>
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

<div class="card border shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-bold mb-0" style="color: #333; font-size: 1.1rem;">Daftar Petugas</h5>
            <button type="button" class="btn btn-success text-white fw-semibold shadow-sm" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a;" data-coreui-toggle="modal" data-coreui-target="#addPetugasModal">
                + Tambah Petugas
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0">
                <thead style="border-bottom: 1px solid var(--bs-border-color); background-color: transparent;">
                    <tr>
                        <th class="text-body-secondary py-3 px-3 fw-semibold" style="font-size: 0.8rem; width: 50px;">No</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Nama Petugas</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Username</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Email</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">No. HP</th>
                        <th class="text-body-secondary py-3 fw-semibold" style="font-size: 0.8rem;">Status</th>
                        <th class="text-body-secondary py-3 fw-semibold text-center" style="font-size: 0.8rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petugas as $index => $item)
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td class="text-center text-body-secondary small py-3 px-3">{{ $index + 1 }}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($item->nama_petugas ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="small fw-semibold" style="color: #333; font-size: 0.85rem;">{{ $item->nama_petugas }}</div>
                                    <div class="text-body-secondary" style="font-size: 0.75rem;">Petugas</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="small" style="color: #333; font-size: 0.85rem;">{{ $item->user->username ?? '-' }}</div>
                        </td>
                        <td class="py-3">
                            <div class="small" style="color: #333; font-size: 0.85rem;">{{ $item->email }}</div>
                        </td>
                        <td class="py-3">
                            <div class="small" style="color: #333; font-size: 0.85rem;">{{ $item->no_hp }}</div>
                        </td>
                        <td class="py-3">
                            @if($item->status_aktif)
                            <span class="badge bg-success" style="font-size: 0.75rem;">Aktif</span>
                            @else
                            <span class="badge bg-secondary" style="font-size: 0.75rem;">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-light" style="border-radius: 6px; padding: 0.3rem 0.6rem;" title="Edit">
                                    <svg style="width: 16px; height: 16px; color: #0d6efd;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('petugas.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus petugas ini?');">
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
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data petugas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addPetugasModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" style="color: #1b5e3a;">Tambah Petugas Baru</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('petugas.store') }}" method="POST">
        @csrf
        <div class="modal-body text-start">
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">Nama Petugas</label>
                <input type="text" name="nama_petugas" class="form-control" placeholder="Masukkan nama lengkap" required style="border-radius: 6px;">
                <div class="form-text small text-body-secondary">Username akan dibuat otomatis dari nama (tanpa spasi)</div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">Email</label>
                <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required style="border-radius: 6px;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">No. HP</label>
                <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required style="border-radius: 6px;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-1" style="color: #1b5e3a;">Password</label>
                <input type="text" name="password" class="form-control" value="petugas123" readonly style="border-radius: 6px; background-color: #f8f9fa;">
                <div class="form-text small text-body-secondary">Password default: petugas123</div>
            </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
            <button type="button" class="btn btn-outline-secondary btn-sm fw-bold" data-coreui-dismiss="modal" style="border-radius: 6px;">Batal</button>
            <button type="submit" class="btn btn-success btn-sm fw-bold shadow-sm" style="border-radius: 6px; background-color: #1b5e3a; border-color: #1b5e3a;">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection