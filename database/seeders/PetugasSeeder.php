<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Seed akun petugas dengan nama sebagai username dan password petugas123
     */
    public function run(): void
    {
        // Daftar petugas yang akan dibuat
        $petugasList = [
            ['nama' => 'Budi Santoso', 'email' => 'budi@sanitacheck.id', 'no_hp' => '081234567890'],
            ['nama' => 'Siti Aisyah', 'email' => 'siti@sanitacheck.id', 'no_hp' => '081234567891'],
            ['nama' => 'Ahmad Rizky', 'email' => 'ahmad@sanitacheck.id', 'no_hp' => '081234567892'],
            ['nama' => 'Dewi Lestari', 'email' => 'dewi@sanitacheck.id', 'no_hp' => '081234567893'],
            ['nama' => 'Eko Prasetyo', 'email' => 'eko@sanitacheck.id', 'no_hp' => '081234567894'],
        ];

        foreach ($petugasList as $petugasData) {
            // Buat atau update user account
            $user = User::updateOrCreate(
                ['username' => strtolower(str_replace(' ', '', $petugasData['nama']))],
                [
                    'name' => $petugasData['nama'],
                    'email' => $petugasData['email'],
                    'password' => Hash::make('petugas123'),
                    'role' => 'petugas',
                ]
            );

            // Buat atau update petugas record dan link ke user
            Petugas::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_petugas' => $petugasData['nama'],
                    'email' => $petugasData['email'],
                    'no_hp' => $petugasData['no_hp'],
                    'status_aktif' => true,
                ]
            );
        }
    }
}
