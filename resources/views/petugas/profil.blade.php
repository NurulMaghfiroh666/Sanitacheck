@extends('layouts.petugas')

@section('title', 'Profil | SanitaCheck')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-person-fill text-success me-2"></i>Profil Saya</h5>

<div class="card border-0 shadow-sm mb-3" style="border-radius: 15px;">
    <div class="card-body p-4 text-center">
        <div class="d-flex justify-content-center mb-3">
            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 72px; height: 72px; font-size: 2rem; font-weight: 700;">
                {{ strtoupper(substr($petugasData->nama_petugas ?? $user->name, 0, 1)) }}
            </div>
        </div>
        <h5 class="fw-bold text-dark mb-1">{{ $petugasData->nama_petugas ?? $user->name }}</h5>
        <p class="text-secondary small mb-1"><i class="bi bi-person-badge me-1"></i>{{ ucfirst($user->role) }}</p>
        <p class="text-secondary small mb-1"><i class="bi bi-envelope me-1"></i>{{ $petugasData->email ?? $user->email ?? '-' }}</p>
        @if($petugasData && $petugasData->no_hp)
        <p class="text-secondary small mb-0"><i class="bi bi-phone me-1"></i>{{ $petugasData->no_hp }}</p>
        @endif
    </div>
</div>

<!-- Statistik Kerja -->
<div class="card border-0 shadow-sm mb-3" style="border-radius: 15px;">
    <div class="card-body p-3">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-line me-2 text-success"></i>Statistik Kerja</h6>
        <div class="row g-2 text-center">
            <div class="col-4">
                <div class="bg-success bg-opacity-10 rounded p-2" style="border-radius: 10px;">
                    <div class="fw-bold fs-5 text-success">{{ $totalInspeksi }}</div>
                    <div class="text-secondary" style="font-size: 0.7rem;">Total Inspeksi</div>
                </div>
            </div>
            <div class="col-4">
                <div class="bg-primary bg-opacity-10 rounded p-2" style="border-radius: 10px;">
                    <div class="fw-bold fs-5 text-primary">{{ $totalJadwal }}</div>
                    <div class="text-secondary" style="font-size: 0.7rem;">Total Jadwal</div>
                </div>
            </div>
            <div class="col-4">
                <div class="bg-warning bg-opacity-10 rounded p-2" style="border-radius: 10px;">
                    <div class="fw-bold fs-5 text-warning">{{ $jadwalSelesai }}</div>
                    <div class="text-secondary" style="font-size: 0.7rem;">Jadwal Selesai</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-3">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-key me-2 text-success"></i>Ubah Password</h6>
        <form action="{{ route('petugas.updatePassword') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Password Lama</label>
                <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password lama" required style="border-radius: 8px;">
                @error('password_lama')
                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Password Baru</label>
                <input type="password" name="password_baru" class="form-control" placeholder="Minimal 8 karakter" required style="border-radius: 8px;">
                @error('password_baru')
                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Konfirmasi Password Baru</label>
                <input type="password" name="password_baru_confirmation" class="form-control" placeholder="Ketik ulang password baru" required style="border-radius: 8px;">
            </div>
            <button type="submit" class="btn btn-success w-100 fw-bold py-2" style="border-radius: 10px; background-color: #1b5e3a; border-color: #1b5e3a;">
                <i class="bi bi-save me-1"></i> Simpan Password
            </button>
        </form>
    </div>
</div>

<div class="mt-3">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100 fw-bold py-2" style="border-radius: 10px;">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
    </form>
</div>
@endsection
