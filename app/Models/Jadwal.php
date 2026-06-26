<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'petugas_id',
        'fasilitas_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_tugas',
        'status',
        'status_aktif',
        'catatan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }

    public function photos()
    {
        return $this->hasMany(InspeksiPhoto::class);
    }
}