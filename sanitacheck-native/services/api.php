<?php
// services/api.php

// Update API_BASE_URL to point to Laravel application
// Adjust the path based on your Laravel installation
define('API_BASE_URL', 'http://localhost:8000/api'); // For local development
// define('API_BASE_URL', 'https://your-domain.com/api'); // For production

$isDirectAccess = basename($_SERVER['SCRIPT_FILENAME']) === 'api.php';

if ($isDirectAccess) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
}

function fetchApi($endpoint, $method = 'GET', $data = []) {
    $url = API_BASE_URL . $endpoint;
    $ch = curl_init();

    if ($method == 'GET' && !empty($data)) $url .= '?' . http_build_query($data);

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        
        // Check if we have a file upload (CURLFile)
        $hasFile = false;
        foreach ($data as $value) {
            if ($value instanceof CURLFile) {
                $hasFile = true;
                break;
            }
        }
        
        if ($hasFile) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json']);
        }
    }

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    error_log("API Request: $url");
    error_log("API Response: $response");
    error_log("HTTP Code: $httpCode, Error: $error");

    if ($error || $httpCode >= 400) {
        return json_encode(['status' => 'error', 'message' => ($error ?: 'HTTP ' . $httpCode)]);
    }
    return $response;
}

function getData($endpoint) {
    $response = fetchApi($endpoint);
    $result = json_decode($response, true);
    if ($result && isset($result['status']) && $result['status'] === 'success') {
        return ['status' => 'success', 'data' => $result['data'] ?? []];
    }
    // Return error with message if available
    return ['status' => 'error', 'data' => [], 'message' => $result['message'] ?? 'Failed to fetch data'];
}

function getFasilitas($search = '', $gedung = '', $status_filter = '') {
    $result = getData('/fasilitas');
    $data = $result['data'];
    if ($search !== '') $data = array_filter($data, fn($i) => stripos($i['nama'] ?? '', $search) !== false);
    if ($gedung !== '') $data = array_filter($data, fn($i) => stripos($i['lokasi'] ?? '', $gedung) !== false);
    if ($status_filter !== '') $data = array_filter($data, fn($i) => stripos($i['status'] ?? '', $status_filter) !== false);
    return ['status' => 'success', 'data' => array_values($data)];
}

function getFasilitasById($id) {
    return getData("/fasilitas/$id");
}

function getInspeksiByFasilitas($id) {
    return getData("/fasilitas/$id/inspeksi");
}

function getKeluhan() {
    return getData('/keluhan');
}

function getStatistik() {
    $response = fetchApi('/statistik');
    $data = json_decode($response, true);
    if ($data && isset($data['status']) && $data['status'] === 'success' && isset($data['data'])) {
        return $data['data'];
    }
    return [
        'persentase_kebersihan' => 0,
        'inspeksi_hari_ini' => 0,
        'perlu_tindak_lanjut' => 0,
        'keluhan_diproses' => 0
    ];
}

// --- ROUTING AJAX ---
if ($isDirectAccess) {
    $action = $_GET['action'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'];

    if ($action === 'lapor' && $method === 'POST') {
        $input = $_POST;
        $file = $_FILES['foto'] ?? null;
        
        if (empty($input['fasilitas_id']) || empty($input['catatan'])) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
            exit;
        }
        
        $payload = [
            'fasilitas_id' => (int)$input['fasilitas_id'],
            'nama_pelapor' => $input['nama_pelapor'] ?? 'Anonim',
            'catatan' => $input['catatan']
        ];
        
        // Handle file upload
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $payload['foto'] = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
        }
        
        $result = fetchApi('/keluhan', 'POST', $payload);
        
        // Log the raw response for debugging
        error_log("Raw API Response: $result");
        
        echo $result;
        exit;
    }

    if ($action === 'data' && $method === 'GET') {
        $type = $_GET['type'] ?? '';
        switch ($type) {
            case 'fasilitas': echo json_encode(getFasilitas()); break;
            case 'keluhan': echo json_encode(getKeluhan()); break;
            case 'statistik': echo json_encode(['status' => 'success', 'data' => getStatistik()]); break;
            default: echo json_encode(['status' => 'error', 'message' => 'Tipe data tidak dikenal.']);
        }
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenal.']);
    exit;
}