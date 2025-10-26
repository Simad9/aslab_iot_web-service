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
$command_id = $input['command_id'] ?? null;
$result     = $input['result'] ?? null;

// Cek apakah command_id ada
if (!$command_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing command_id']);
    exit;
}

// Update status command
$stmt = $pdo->prepare("UPDATE commands
                       SET status = 'executed', executed_at = NOW(), payload = :result
                       WHERE id = :id");
$stmt->execute([
    ':result' => $result,
    ':id'     => $command_id
]);

// Cek apakah update berhasil
if ($stmt->rowCount() > 0) {
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Update command failed']);
    exit;
}
