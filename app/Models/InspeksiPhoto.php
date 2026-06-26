<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeksiPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id',
        'petugas_id',
        'foto_path',
        'keterangan'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }
}
