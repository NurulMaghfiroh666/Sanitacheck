<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Petugas;
use App\Models\Fasilitas;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    /**
     * Seed data jadwal inspeksi untuk petugas
     */
    public function run(): void
    {
        // Ambil data petugas dan fasilitas
        $petugasList = Petugas::with('user')->where('status_aktif', true)->get();
        $fasilitasList = Fasilitas::where('status_aktif', true)->get();

        if ($petugasList->isEmpty() || $fasilitasList->isEmpty()) {
            $this->command->warn('Data petugas atau fasilitas kosong. Jalankan PetugasSeeder dan FasilitasSeeder terlebih dahulu.');
            return;
        }

        // Hapus jadwal lama untuk menghindari duplikasi
        Jadwal::query()->delete();

        // Buat jadwal untuk setiap petugas
        $today = Carbon::now();
        
        $jadwalData = [];

        foreach ($petugasList as $index => $petugas) {
            // Setiap petugas mendapat 2-3 jadwal
            $numJadwal = rand(2, 3);
            
            for ($i = 0; $i < $numJadwal; $i++) {
                // Pilih fasilitas secara acak
                $fasilitas = $fasilitasList->random();
                
                // Tentukan tanggal (mulai dari hari ini sampai 7 hari ke depan)
                $startDate = $today->copy()->addDays(rand(0, 7));
                $endDate = $startDate->copy()->addDays(rand(1, 3));
                
                // Pilih jenis tugas secara acak
                $jenisTugas = ['Inspeksi Sanitasi', 'Pembersihan Rutin', 'Pemeliharaan'][rand(0, 2)];
                
                // Tentukan status
                $status = ['Menunggu', 'Berlangsung', 'Selesai'][rand(0, 2)];
                
                $jadwalData[] = [
                    'petugas_id' => $petugas->id,
                    'fasilitas_id' => $fasilitas->id,
                    'tanggal_mulai' => $startDate,
                    'tanggal_selesai' => $endDate,
                    'jenis_tugas' => $jenisTugas,
                    'status' => $status,
                    'status_aktif' => true,
                    'catatan' => 'Jadwal inspeksi rutin untuk ' . $fasilitas->nama_fasilitas,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert semua jadwal
        Jadwal::insert($jadwalData);

        $this->command->info('Data jadwal berhasil ditambahkan! Total: ' . count($jadwalData) . ' jadwal.');
    }
}
