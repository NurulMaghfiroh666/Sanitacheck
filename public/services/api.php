<?php
// services/api.php

/**
 * Fungsi ini menyimulasikan panggilan (fetch) ke REST API Laravel.
 * Di dunia nyata, Anda akan mengganti ini dengan cURL atau file_get_contents
 * ke URL backend Laravel yang sebenarnya (misal: http://localhost:8000/api/...).
 */
function fetchApi($endpoint, $method = 'GET', $data = []) {
    // 1. Data Dummy Fasilitas
    $fasilitas_data = [
        [
            'id' => 1,
            'nama' => 'Toilet Lantai 1',
            'jenis' => 'Toilet Umum',
            'lokasi' => 'Gedung A',
            'status' => 'Bersih',
            'terakhir_inspeksi' => '2025-05-22 09:15',
            'petugas' => 'Budi Santoso'
        ],
        [
            'id' => 2,
            'nama' => 'Kantin Sehat',
            'jenis' => 'Kantin',
            'lokasi' => 'Gedung B',
            'status' => 'Perlu Perhatian',
            'terakhir_inspeksi' => '2025-05-21 14:20',
            'petugas' => 'Siti Aisyah'
        ],
        [
            'id' => 3,
            'nama' => 'Ruang Tunggu',
            'jenis' => 'Ruang Umum',
            'lokasi' => 'Gedung C',
            'status' => 'Perlu Perbaikan',
            'terakhir_inspeksi' => '2025-05-20 10:30',
            'petugas' => 'Rina Marlina'
        ],
        [
            'id' => 4,
            'nama' => 'Toilet Perempuan',
            'jenis' => 'Toilet Umum',
            'lokasi' => 'Gedung A',
            'status' => 'Bersih',
            'terakhir_inspeksi' => '2025-05-19 08:45',
            'petugas' => 'Budi Santoso'
        ],
        [
            'id' => 5,
            'nama' => 'Tempat Cuci Tangan',
            'jenis' => 'Fasilitas Cuci',
            'lokasi' => 'Halaman Depan',
            'status' => 'Bersih',
            'terakhir_inspeksi' => '2025-05-18 15:30',
            'petugas' => 'Joko Purwanto'
        ]
    ];

    $riwayat_inspeksi_data = [
        1 => [
            ['tanggal' => '2025-05-22', 'kondisi' => 'Lantai bersih, air lancar', 'status' => 'Bersih'],
            ['tanggal' => '2025-05-20', 'kondisi' => 'Sedikit kotor di wastafel', 'status' => 'Perlu Perhatian'],
            ['tanggal' => '2025-05-18', 'kondisi' => 'Lantai bersih, sabun habis', 'status' => 'Perlu Perhatian'],
        ],
        2 => [
            ['tanggal' => '2025-05-21', 'kondisi' => 'Meja berantakan', 'status' => 'Perlu Perhatian'],
            ['tanggal' => '2025-05-19', 'kondisi' => 'Bersih dan rapi', 'status' => 'Bersih'],
        ]
    ];

    // GET /api/fasilitas
    if ($endpoint === '/api/fasilitas' && $method === 'GET') {
        return json_encode([
            'status' => 'success',
            'data' => $fasilitas_data
        ]);
    }

    // GET /api/fasilitas/status/{status}
    if (preg_match('/^\/api\/fasilitas\/status\/(.+)$/', $endpoint, $matches) && $method === 'GET') {
        $status_param = urldecode($matches[1]);
        $filtered = array_filter($fasilitas_data, function($item) use ($status_param) {
            return strtolower($item['status']) === strtolower($status_param);
        });
        return json_encode([
            'status' => 'success',
            'data' => array_values($filtered)
        ]);
    }

    // GET /api/fasilitas/{id}
    if (preg_match('/^\/api\/fasilitas\/([0-9]+)$/', $endpoint, $matches) && $method === 'GET') {
        $id = (int)$matches[1];
        $found = null;
        foreach ($fasilitas_data as $f) {
            if ($f['id'] === $id) {
                $found = $f; break;
            }
        }
        return json_encode([
            'status' => $found ? 'success' : 'error',
            'data' => $found
        ]);
    }

    // GET /api/fasilitas/{id}/inspeksi
    if (preg_match('/^\/api\/fasilitas\/([0-9]+)\/inspeksi$/', $endpoint, $matches) && $method === 'GET') {
        $id = (int)$matches[1];
        $riwayat = isset($riwayat_inspeksi_data[$id]) ? $riwayat_inspeksi_data[$id] : [
            ['tanggal' => '2025-05-20', 'kondisi' => 'Inspeksi standar, tidak ada isu major', 'status' => 'Bersih']
        ];
        return json_encode([
            'status' => 'success',
            'data' => $riwayat
        ]);
    }

    // POST /api/inspeksi-sanitasi (untuk lapor)
    if ($endpoint === '/api/inspeksi-sanitasi' && $method === 'POST') {
        // Simulasi sukses
        return json_encode([
            'status' => 'success',
            'message' => 'Laporan berhasil dikirim.'
        ]);
    }

    return json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
}

/**
 * Fungsi helper untuk mendapatkan statistik (beranda)
 * Di real case ini mungkin didapat dari endpoint `/api/statistik`
 */
function getStatistik() {
    return [
        'persentase_kebersihan' => 92,
        'inspeksi_hari_ini' => 58,
        'perlu_tindak_lanjut' => 6,
        'keluhan_diproses' => 14
    ];
}
?>
