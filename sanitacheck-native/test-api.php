<?php
require_once 'services/api.php';

echo "<h2>Testing API Connection</h2>";
echo "<h3>API_BASE_URL: " . API_BASE_URL . "</h3>";

// Test statistik
echo "<h3>1. Testing /api/statistik</h3>";
$stats = getStatistik();
echo "<pre>";
print_r($stats);
echo "</pre>";

// Test fasilitas
echo "<h3>2. Testing /api/fasilitas</h3>";
$fasilitas = getFasilitas();
echo "<pre>";
print_r($fasilitas);
echo "</pre>";

echo "<h3>3. Status: " . ($fasilitas['status'] ?? 'unknown') . "</h3>";
echo "<h3>4. Count of fasilitas: " . count($fasilitas['data'] ?? []) . "</h3>";

if (!empty($fasilitas['data'])) {
    echo "<h3>5. First 3 fasilitas:</h3>";
    echo "<pre>";
    print_r(array_slice($fasilitas['data'], 0, 3));
    echo "</pre>";
} else {
    echo "<h3>5. No fasilitas data found!</h3>";
    echo "<p>Error message: " . ($fasilitas['message'] ?? 'No message') . "</p>";
}

// Test direct curl
echo "<h3>6. Testing direct curl to Laravel API</h3>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/fasilitas');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<p>HTTP Code: $httpCode</p>";
echo "<p>Error: $error</p>";
echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";
