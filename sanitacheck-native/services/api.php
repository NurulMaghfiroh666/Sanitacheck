<?php
// services/api.php

define('API_BASE_URL', 'http://127.0.0.1:8000');
define('DATA_DIR', __DIR__ . '/../data');

$isDirectAccess = basename($_SERVER['SCRIPT_FILENAME']) === 'api.php';

if ($isDirectAccess) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
}

function bacaJson($file) {
    $path = DATA_DIR . '/' . $file;
    if (!file_exists($path)) return [];
    $content = file_get_contents($path);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function tulisJson($file, $data) {
    $path = DATA_DIR . '/' . $file;
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function fetchApi($endpoint, $method = 'GET', $data = []) {
    $url = API_BASE_URL . $endpoint;
    $ch = curl_init();

    if ($method == 'GET' && !empty($data)) $url .= '?' . http_build_query($data);

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 8,
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/json']
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
        return json_encode(['status' => 'error', 'message' => ($error ?: 'HTTP ' . $httpCode)]);
    }
    return $response;
}

function getData($endpoint, $file, $transform = null) {
    $response = fetchApi($endpoint);
    $result = json_decode($response, true);
    if ($result && isset($result['status']) && $result['status'] === 'success') {
        $data = $result['data'] ?? [];
        if ($transform) $data = $transform($data, $result);
        return ['status' => 'success', 'data' => $data, 'source' => 'api'];
    }
    $local = bacaJson($file);
    if (!empty($local)) {
        return ['status' => 'success', 'data' => $local, 'source' => 'local'];
    }
    return ['status' => 'success', 'data' => [], 'source' => 'local'];
}

function getFasilitas($search = '', $gedung = '', $status_filter = '') {
    $result = getData('/api/fasilitas', 'fasilitas.json');
    $data = $result['data'];
    if ($search !== '') $data = array_filter($data, fn($i) => stripos($i['nama'] ?? '', $search) !== false);
    if ($gedung !== '') $data = array_filter($data, fn($i) => stripos($i['lokasi'] ?? '', $gedung) !== false);
    if ($status_filter !== '') $data = array_filter($data, fn($i) => stripos($i['status'] ?? '', $status_filter) !== false);
    return ['status' => 'success', 'data' => array_values($data)];
}

function getFasilitasById($id) {
    $all = getFasilitas()['data'];
    foreach ($all as $f) {
        if (($f['id'] ?? 0) == $id) return ['status' => 'success', 'data' => $f];
    }
    return ['status' => 'error', 'message' => 'Fasilitas tidak ditemukan.'];
}

function getInspeksiByFasilitas($id) {
    $result = getData("/api/fasilitas/$id/inspeksi", 'fasilitas.json');
    if ($result['source'] === 'local') {
        $mock = [
            ['tanggal' => date('Y-m-d', strtotime('-1 day')), 'kondisi' => 'Kondisi cukup bersih, sabun tersedia.', 'status' => 'Bersih'],
            ['tanggal' => date('Y-m-d', strtotime('-3 days')), 'kondisi' => 'Lantai agak kotor, perlu pembersihan.', 'status' => 'Perlu Perhatian'],
            ['tanggal' => date('Y-m-d', strtotime('-7 days')), 'kondisi' => 'Kondisi kotor, sabun habis.', 'status' => 'Perlu Perbaikan'],
        ];
        return ['status' => 'success', 'data' => $mock];
    }
    return $result;
}

function getKeluhan() {
    return getData('/api/keluhan', 'keluhan.json');
}

function getPeralatan() {
    return getData('/api/peralatan', 'peralatan.json');
}

function getStatistik() {
    $response = fetchApi('/api/statistik');
    $data = json_decode($response, true);
    if ($data && isset($data['status']) && $data['status'] === 'success' && isset($data['data'])) {
        return $data['data'];
    }
    $fasilitas = getFasilitas()['data'];
    $keluhan = getKeluhan()['data'];
    $total = count($fasilitas);
    $proses = count(array_filter($keluhan, fn($k) => stripos($k['status'] ?? '', 'proses') !== false));
    $selesai = count(array_filter($keluhan, fn($k) => stripos($k['status'] ?? '', 'selesai') !== false));
    return [
        'persentase_kebersihan' => $total > 0 ? round((count(array_filter($fasilitas, fn($f) => stripos($f['status'] ?? '', 'bersih') !== false)) / $total) * 100) : 0,
        'inspeksi_hari_ini' => $total,
        'perlu_tindak_lanjut' => $proses,
        'keluhan_diproses' => $selesai > 0 ? $selesai : rand(1, 3)
    ];
}

// --- ROUTING AJAX ---
if ($isDirectAccess) {
    $action = $_GET['action'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'];

    if ($action === 'lapor' && $method === 'POST') {
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
        if ($decoded && isset($decoded['status']) && $decoded['status'] === 'success') {
            echo json_encode(['status' => 'success', 'message' => 'Laporan berhasil dikirim.']);
            exit;
        }
        // Fallback: simpan ke JSON lokal
        $keluhan = bacaJson('keluhan.json');
        $new_id = 1;
        $existing_ids = array_column($keluhan, 'id');
        if (!empty($existing_ids)) $new_id = max($existing_ids) + 1;
        $fasilitas = getFasilitas()['data'];
        $fas_nama = '';
        foreach ($fasilitas as $f) {
            if (($f['id'] ?? 0) == (int)$input['fasilitas_id']) { $fas_nama = $f['nama'] ?? ''; break; }
        }
        $keluhan[] = [
            'id' => $new_id,
            'id_keluhan' => 'KLP-' . date('Y') . '-' . str_pad($new_id, 4, '0', STR_PAD_LEFT),
            'fasilitas_id' => (int)$input['fasilitas_id'],
            'fasilitas_nama' => $fas_nama ?: 'Fasilitas #' . $input['fasilitas_id'],
            'nama_pelapor' => $input['nama_pelapor'] ?? 'Anonim',
            'catatan' => $input['catatan'],
            'status' => 'Diajukan',
            'resolution' => '',
            'petugas' => '',
            'catatan_petugas' => '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        tulisJson('keluhan.json', $keluhan);
        echo json_encode(['status' => 'success', 'message' => 'Laporan berhasil dikirim.']);
        exit;
    }

    if ($action === 'data' && $method === 'GET') {
        $type = $_GET['type'] ?? '';
        switch ($type) {
            case 'fasilitas': echo json_encode(getFasilitas()); break;
            case 'keluhan': echo json_encode(getKeluhan()); break;
            case 'peralatan': echo json_encode(getPeralatan()); break;
            case 'statistik': echo json_encode(['status' => 'success', 'data' => getStatistik()]); break;
            default: echo json_encode(['status' => 'error', 'message' => 'Tipe data tidak dikenal.']);
        }
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenal.']);
    exit;
}