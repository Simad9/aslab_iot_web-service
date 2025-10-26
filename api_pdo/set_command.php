<?php
require '../config/koneksi.php';

// Baca input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Cek Input JSON
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Tidak ada data JSON diterima']);
    exit;
}

// Cek data JSON di API nya (jika tidak mau hapus kode ini)
// if (!$input || !isset($input['api_key']) || $input['api_key'] !== $API_KEY) {
//     http_response_code(401);
//     echo json_encode(['error' => 'Unauthorized']);
//     exit;
// }

// Nangkap Data JSON
$device_id = $input['device_id'] ?? null;
$command   = $input['command'] ?? null;
$payload   = $input['payload'] ?? null;

// Cek apakah device_id dan command ada
if (!$device_id || !$command) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Escape data untuk keamanan
$device_id = $pdo->quote($device_id);
$command   = $pdo->quote($command);
$payload   = $pdo->quote($payload);

// Simpan perintah baru
$stmt = $pdo->prepare("INSERT INTO commands (device_id, command, payload)
                       VALUES (:device_id, :cmd, :payload)");
$stmt->execute([
    ':device_id' => $device_id,
    ':cmd'       => $command,
    ':payload'   => $payload
]);

// Cek apakah simpan berhasil
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save command']);
    exit;
}

// Kembalikan response
echo json_encode([
    'status' => 'ok',
    'command_id' => $pdo->lastInsertId()
]);
