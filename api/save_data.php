<?php
require '../config/koneksi.php';

// Baca input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Cek data JSON di API nya (jika tidak mau hapus kode ini)
// if (!$input || !isset($input['api_key']) || $input['api_key'] !== $API_KEY) {
//     http_response_code(401);
//     echo json_encode(['error' => 'Unauthorized']);
//     exit;
// }

// Nangkap Data JSON
$device_id   = $input['device_id'] ?? null;
$sensor_type = $input['sensor_type'] ?? 'unknown';
$value       = $input['value'] ?? null;
$raw         = $input['raw'] ?? null;

// Cek apakah device_id dan value ada
if (!$device_id || $value === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Simpan / update device
$sql = "INSERT INTO devices (device_id, last_seen) VALUES ('$device_id', NOW()) ON DUPLICATE KEY UPDATE last_seen = NOW()";
$result = mysqli_query($koneksi, $sql);

// Error Handling
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
    exit;
}

// Simpan data sensor
$sql = "INSERT INTO sensor_data (device_id, sensor_type, value, raw_value) VALUES ('$device_id', '$sensor_type', $value, '$raw')";
mysqli_query($koneksi, $sql);

// Error Handling
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
    exit;
}

// Kembalikan status OK
echo json_encode(['status' => 'ok']);
