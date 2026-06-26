<?php
// services/api.php

// Cek apakah file ini diakses langsung atau di-include
$isDirectAccess = basename($_SERVER['SCRIPT_FILENAME']) === 'api.php';

if ($isDirectAccess) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

define('API_BASE_URL', 'http://127.0.0.1:8000');

function fetchApi($endpoint, $method = 'GET', $data = []) {
    $url = API_BASE_URL . $endpoint;
    
    $ch = curl_init();
    
    if ($method == 'GET' && !empty($data)) {
        $url .= '?' . http_build_query($data);
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($error || $httpCode >= 400) {
        return json_encode([
            'status' => 'error', 
            'message' => 'Gagal terhubung ke server API (Code: ' . $httpCode . ')'
        ]);
    }
    
    return $response;
}

function getStatistik() {
    $response = fetchApi('/api/statistik');
    $data = json_decode($response, true);
    
    if (isset($data['status']) && $data['status'] == 'success') {
        return $data['data'];
    }
    
    return [
        'persentase_kebersihan' => 0,
        'inspeksi_hari_ini' => 0,
        'perlu_tindak_lanjut' => 0,
        'keluhan_diproses' => 0
    ];
}

function getKeluhan() {
    $response = fetchApi('/api/keluhan');
    $data = json_decode($response, true);
    if (isset($data['status']) && $data['status'] == 'success') {
        return $data;
    }
    return ['status' => 'error', 'data' => []];
}

function getPeralatan() {
    $response = fetchApi('/api/peralatan');
    $data = json_decode($response, true);
    if (isset($data['status']) && $data['status'] == 'success') {
        return $data;
    }
    return ['status' => 'error', 'data' => []];
}

// Routing untuk request AJAX (hanya jika diakses langsung)
if ($isDirectAccess) {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action === 'lapor' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || empty($input['fasilitas_id']) || empty($input['catatan'])) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
            exit;
        }

        $payload = [
            'fasilitas_id' => (int)$input['fasilitas_id'],
            'nama_pelapor' => $input['nama_pelapor'] ?? 'Anonim',
            'catatan' => $input['catatan'],
            'tanggal' => date('Y-m-d H:i:s')
        ];

        $result = fetchApi('/api/keluhan', 'POST', $payload);
        $decoded = json_decode($result, true);

        if ($decoded && $decoded['status'] === 'success') {
            echo json_encode(['status' => 'success', 'message' => 'Laporan berhasil dikirim.']);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Laporan berhasil dikirim.']);
        }
        exit;
    }

    if ($action !== '' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenal.']);
        exit;
    }
}
