<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fasilitas;

class FasilitasSeeder extends Seeder
{
    /**
     * Seed data fasilitas sanitasi
     */
    public function run(): void
    {
        $fasilitasList = [
            [
                'nama_fasilitas' => 'Toilet Lantai 1 - Gedung A',
                'jenis_fasilitas' => 'toilet',
                'lokasi' => 'Gedung A, Lantai 1',
                'penanggung_jawab' => 'Tim Kebersihan A',
                'status_aktif' => true,
            ],
            [
                'nama_fasilitas' => 'Toilet Lantai 2 - Gedung A',
                'jenis_fasilitas' => 'toilet',
                'lokasi' => 'Gedung A, Lantai 2',
                'penanggung_jawab' => 'Tim Kebersihan A',
                'status_aktif' => true,
            ],
            [
                'nama_fasilitas' => 'Toilet Lantai 1 - Gedung B',
                'jenis_fasilitas' => 'toilet',
                'lokasi' => 'Gedung B, Lantai 1',
                'penanggung_jawab' => 'Tim Kebersihan B',
                'status_aktif' => true,
            ],
            [
                'nama_fasilitas' => 'Toilet Lantai 2 - Gedung B',
                'jenis_fasilitas' => 'toilet',
                'lokasi' => 'Gedung B, Lantai 2',
                'penanggung_jawab' => 'Tim Kebersihan B',
                'status_aktif' => true,
            ],
            [
                'nama_fasilitas' => 'Wastafel Umum - Lobby',
                'jenis_fasilitas' => 'tempat cuci tangan',
                'lokasi' => 'Lobby Utama',
                'penanggung_jawab' => 'Tim Kebersihan Umum',
                'status_aktif' => true,
            ],
            [
                'nama_fasilitas' => 'Depot Air Minum - Kantin',
                'jenis_fasilitas' => 'kantin',
                'lokasi' => 'Area Kantin',
                'penanggung_jawab' => 'Tim Kebersihan Kantin',
                'status_aktif' => true,
            ],
            [
                'nama_fasilitas' => 'Depot Air Minum - Lobby',
                'jenis_fasilitas' => 'kantin',
                'lokasi' => 'Lobby Utama',
                'penanggung_jawab' => 'Tim Kebersihan Umum',
                'status_aktif' => true,
            ],
            [
                'nama_fasilitas' => 'Toilet VIP - Ruang Tunggu',
                'jenis_fasilitas' => 'toilet',
                'lokasi' => 'Ruang Tunggu VIP',
                'penanggung_jawab' => 'Tim Kebersihan VIP',
                'status_aktif' => true,
            ],
        ];

        foreach ($fasilitasList as $fasilitasData) {
            Fasilitas::updateOrCreate(
                ['nama_fasilitas' => $fasilitasData['nama_fasilitas'], 'lokasi' => $fasilitasData['lokasi']],
                $fasilitasData
            );
        }

        $this->command->info('Data fasilitas berhasil ditambahkan!');
    }
}
