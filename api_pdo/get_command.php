<?php
require '../config/koneksi.php';

// Tangkap data JSON
$device_id = $_GET['device_id'] ?? null;
$api_key   = $_GET['api_key'] ?? null;

// Cek data JSON di API nya (jika tidak mau hapus kode ini)
// if ($api_key !== $API_KEY) {
//     http_response_code(401);
//     echo json_encode(['error' => 'Unauthorized']);
//     exit;
// }

// Cek apakah device_id ada
if (!$device_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing device_id']);
    exit;
}

// Ambil perintah pending paling awal
$stmt = $pdo->prepare("SELECT id, command, payload FROM commands
                       WHERE device_id = :id AND status = 'pending'
                       ORDER BY created_at ASC LIMIT 1");
$stmt->execute([':id' => $device_id]);
$cmd = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika ada perintah pending, kembalikan perintah tersebut
if ($cmd) {
    echo json_encode([
        'status' => 'ok',
        'command_id' => $cmd['id'],
        'command' => $cmd['command'],
        'payload' => $cmd['payload']
    ]);
} else {
    echo json_encode(['status' => 'idle']);
}

