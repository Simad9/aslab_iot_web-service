<?php
require '../config/koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);

// if (!$input || !isset($input['api_key']) || $input['api_key'] !== $API_KEY) {
//     http_response_code(401);
//     echo json_encode(['error' => 'Unauthorized']);
//     exit;
// }

$command_id = $input['command_id'] ?? null;
$result     = $input['result'] ?? null;

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

echo json_encode(['status' => 'ok']);

