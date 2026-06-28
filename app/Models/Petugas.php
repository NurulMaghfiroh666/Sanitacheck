<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $fillable = [
        'user_id',
        'nama_petugas',
        'email',
        'no_hp',
        'status_aktif',
        'foto',
        'total_score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function inspeksiPhotos()
    {
        return $this->hasMany(InspeksiPhoto::class);
    }
}
