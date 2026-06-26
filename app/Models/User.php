<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'username', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'user_id');
    }

    public function inspeksis()
    {
        return $this->hasMany(Inspeksi::class, 'petugas_id');
    }

    public function petugas()
    {
        return $this->hasOne(Petugas::class, 'user_id');
    }

    public function tugasInspeksi()
    {
        return $this->belongsToMany(TugasInspeksi::class, 'tugas_inspeksi_user')
            ->withPivot('joined_at')
            ->withTimestamps();
    }
}