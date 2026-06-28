<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasInspeksi extends Model
{
    protected $fillable = [
        'fasilitas_id',
        'ketua_id',
        'judul',
        'deskripsi',
        'kuota_tim',
        'tanggal_tugas',
        'status',
        'approval_status',
        'laporan_catatan',
        'laporan_foto',
        'kondisi_kebersihan',
        'laporan_submitted_at',
    ];

    protected $casts = [
        'tanggal_tugas' => 'datetime',
        'laporan_submitted_at' => 'datetime',
    ];

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }

    public function ketua()
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tugas_inspeksi_user')
            ->withPivot('joined_at', 'voted_ketua_id')
            ->withTimestamps();
    }

    public function getPetugasTerdaftarAttribute()
    {
        return $this->users()->count();
    }

    public function isFull()
    {
        return $this->petugas_terdaftar >= $this->kuota_tim;
    }

    public function isUserJoined($userId)
    {
        return $this->users()->where('user_id', $userId)->exists();
    }

    public function isKetua($userId)
    {
        return $this->ketua_id === $userId;
    }

    public function hasReport()
    {
        return !empty($this->laporan_catatan) || !empty($this->laporan_foto);
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    public function getVotingStatusAttribute()
    {
        $teamMembers = $this->users;
        $totalMembers = $teamMembers->count();
        
        if ($totalMembers <= 1) {
            return [
                'status' => 'not_applicable',
                'message' => 'Perlu minimal 2 anggota untuk voting'
            ];
        }

        $votes = $teamMembers->pluck('pivot.voted_ketua_id')->filter();
        $votedCount = $votes->count();
        
        if ($votedCount === 0) {
            return [
                'status' => 'not_started',
                'message' => "Belum ada yang memilih (0/{$totalMembers})"
            ];
        }

        if ($votedCount < $totalMembers) {
            return [
                'status' => 'in_progress',
                'message' => "Sedang voting ({$votedCount}/{$totalMembers})"
            ];
        }

        // All voted, check if unanimous
        $uniqueVotes = $votes->unique();
        if ($uniqueVotes->count() === 1) {
            $selectedKetuaId = $votes->first();
            $selectedKetua = $teamMembers->where('id', $selectedKetuaId)->first();
            return [
                'status' => 'unanimous',
                'message' => 'Semua memilih ketua yang sama',
                'ketua_id' => $selectedKetuaId,
                'ketua_name' => $selectedKetua ? ($selectedKetua->petugas->nama_petugas ?? $selectedKetua->name) : null
            ];
        }

        return [
            'status' => 'conflict',
            'message' => 'Pilihan tidak sama, perlu voting ulang'
        ];
    }

    public function hasUserVoted($userId)
    {
        return $this->users()->where('user_id', $userId)->whereNotNull('voted_ketua_id')->exists();
    }

    public function getUserVote($userId)
    {
        $user = $this->users()->where('user_id', $userId)->first();
        return $user ? $user->pivot->voted_ketua_id : null;
    }
}
