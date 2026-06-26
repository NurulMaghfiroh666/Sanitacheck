<?php
// services/api.php

// URL Backend Laravel Anda (Sesuaikan dengan domain atau IP hosting nantinya)
define('API_BASE_URL', 'http://127.0.0.1:8000'); 

/**
 * Fungsi untuk mengambil data dari REST API Backend
 */
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
        // Fallback jika API mati atau error
        return json_encode([
            'status' => 'error', 
            'message' => 'Gagal terhubung ke server API (Code: ' . $httpCode . ')'
        ]);
    }
    
    return $response;
}

/**
 * Fungsi helper untuk mendapatkan statistik beranda
 */
function getStatistik() {
    $response = fetchApi('/api/statistik');
    $data = json_decode($response, true);
    
    if (isset($data['status']) && $data['status'] == 'success') {
        return $data['data'];
    }
    
    // Default fallback jika API gagal
    return [
        'persentase_kebersihan' => 0,
        'inspeksi_hari_ini' => 0,
        'perlu_tindak_lanjut' => 0,
        'keluhan_diproses' => 0
    ];
}
?>
