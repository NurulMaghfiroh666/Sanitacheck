<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Urutan seeder penting untuk menjaga referensi antar tabel.
     */
    public function run(): void
    {
        $this->command->info('Memulai seeding database...');
        
        // 1. Seed Admin (User account untuk admin)
        $this->call(AdminSeeder::class);
        
        // 2. Seed Petugas (User accounts + Petugas records yang terhubung)
        $this->call(PetugasSeeder::class);
        
        // 3. Seed Fasilitas (Data fasilitas sanitasi)
        $this->call(FasilitasSeeder::class);
        
        // 4. Seed Jadwal (Jadwal inspeksi yang terhubung ke petugas dan fasilitas)
        $this->call(JadwalSeeder::class);
        
        $this->command->info('Seeding database selesai!');
        $this->command->info('=====================================');
        $this->command->info('Akun Admin:');
        $this->command->info('  Email: admin@sanitacheck.id');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('Akun Petugas (Username = nama tanpa spasi, lowercase):');
        $this->command->info('  - budisantoso / petugas123');
        $this->command->info('  - sitiaisyah / petugas123');
        $this->command->info('  - ahmadrizky / petugas123');
        $this->command->info('  - dewilestari / petugas123');
        $this->command->info('  - ekoprasetyo / petugas123');
        $this->command->info('=====================================');
    }
}
