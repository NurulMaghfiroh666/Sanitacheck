<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasInspeksi extends Model
{
    protected $fillable = [
        'fasilitas_id',
        'judul',
        'deskripsi',
        'kuota_tim',
        'tanggal_tugas',
        'status',
    ];

    protected $casts = [
        'tanggal_tugas' => 'datetime',
    ];

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tugas_inspeksi_user')
            ->withPivot('joined_at')
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
}
