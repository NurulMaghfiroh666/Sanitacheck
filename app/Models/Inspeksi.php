<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Inspeksi extends Model
{
    protected $fillable = [
        'fasilitas_id',
        'petugas_id',
        'tanggal_inspeksi',
        'kondisi_kebersihan',
        'ketersediaan_air',
        'ketersediaan_sabun',
        'bau_tidak_sedap',
        'catatan',
        'status_tindak_lanjut',
        'skor'
    ];

    protected $casts = [
        'tanggal_inspeksi' => 'datetime',
    ];

    protected function ketersediaanAir(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? 'Ada' : 'Tidak Ada',
            set: fn ($value) => in_array(strtolower((string)$value), ['1', 'ada', 'ya', 'true']) ? 1 : 0,
        );
    }

    protected function ketersediaanSabun(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? 'Ada' : 'Tidak Ada',
            set: fn ($value) => in_array(strtolower((string)$value), ['1', 'ada', 'ya', 'true']) ? 1 : 0,
        );
    }

    protected function bauTidakSedap(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? 'Ya' : 'Tidak',
            set: fn ($value) => in_array(strtolower((string)$value), ['1', 'ya', 'ada', 'true']) ? 1 : 0,
        );
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }
}
