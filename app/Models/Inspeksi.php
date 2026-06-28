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

    /**
     * Calculate score based on inspection parameters
     * Scoring logic:
     * - Kondisi Kebersihan: Baik=100, Cukup=60, Buruk=20
     * - Ketersediaan Air: Ada=25, Tidak Ada=0
     * - Ketersediaan Sabun: Ada=25, Tidak Ada=0
     * - Bau Tidak Sedap: Tidak=25, Ya=0
     * Total max score: 175
     */
    public function calculateScore()
    {
        $score = 0;

        // Kondisi Kebersihan (max 100)
        switch ($this->kondisi_kebersihan) {
            case 'Baik':
                $score += 100;
                break;
            case 'Cukup':
                $score += 60;
                break;
            case 'Buruk':
                $score += 20;
                break;
        }

        // Ketersediaan Air (max 25)
        if ($this->ketersediaan_air) {
            $score += 25;
        }

        // Ketersediaan Sabun (max 25)
        if ($this->ketersediaan_sabun) {
            $score += 25;
        }

        // Bau Tidak Sedap (max 25 - inverted, no bad smell is good)
        if (!$this->bau_tidak_sedap) {
            $score += 25;
        }

        return $score;
    }

    /**
     * Calculate normalized score (0-100 scale)
     */
    public function calculateNormalizedScore()
    {
        $maxScore = 175;
        $actualScore = $this->calculateScore();
        return round(($actualScore / $maxScore) * 100);
    }
}
