<?php
require '../config/koneksi.php';

// Cek apakah device_id ada
$device_id = $_GET['device_id'] ?? null;

// Jika tidak ada kembalikan error
if (!$device_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing device_id']);
    exit;
}

// Ambil 50 data terakhir
$stmt = $pdo->prepare("SELECT value, recorded_at FROM sensor_data
                       WHERE device_id = :id
                       ORDER BY recorded_at DESC
                       LIMIT 50");
$stmt->execute([':id' => $device_id]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo json_encode($data);
echo json_encode($data);
