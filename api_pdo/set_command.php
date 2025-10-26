<?php
require '../config/koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['api_key']) || $input['api_key'] !== $API_KEY) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$device_id = $input['device_id'] ?? null;
$command   = $input['command'] ?? null;
$payload   = $input['payload'] ?? null;

if (!$device_id || !$command) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Simpan perintah baru
$stmt = $pdo->prepare("INSERT INTO commands (device_id, command, payload)
                       VALUES (:device_id, :cmd, :payload)");
$stmt->execute([
    ':device_id' => $device_id,
    ':cmd'       => $command,
    ':payload'   => $payload
]);

echo json_encode([
    'status' => 'ok',
    'command_id' => $pdo->lastInsertId()
]);
