<?php
require '../config/koneksi.php';

// Baca input JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['api_key']) || $input['api_key'] !== $API_KEY) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$device_id   = $input['device_id'] ?? null;
$sensor_type = $input['sensor_type'] ?? 'unknown';
$value       = $input['value'] ?? null;
$raw         = $input['raw'] ?? null;

if (!$device_id || $value === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Simpan / update device
$stmt = $pdo->prepare("INSERT INTO devices (device_id, last_seen) VALUES (:id, NOW())
                       ON DUPLICATE KEY UPDATE last_seen = NOW()");
$stmt->execute([':id' => $device_id]);

// Simpan data sensor
$stmt = $pdo->prepare("INSERT INTO sensor_data (device_id, sensor_type, value, raw_value)
                       VALUES (:device_id, :stype, :value, :raw)");
$stmt->execute([
    ':device_id' => $device_id,
    ':stype'     => $sensor_type,
    ':value'     => $value,
    ':raw'       => $raw
]);

echo json_encode(['status' => 'ok']);

