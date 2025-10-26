<?php
require '../config/koneksi.php';

$device_id = $_GET['device_id'] ?? null;
if (!$device_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing device_id']);
    exit;
}

// Ambil 50 data terakhir
// $stmt = $pdo->prepare("SELECT value, recorded_at FROM sensor_data
//                        WHERE device_id = :id
//                        ORDER BY recorded_at DESC
//                        LIMIT 50");
// $stmt->execute([':id' => $device_id]);
// $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT value, recorded_at FROM sensor_data
        WHERE device_id = '$device_id'
        ORDER BY recorded_at DESC
        LIMIT 50";
$result = mysqli_query($koneksi, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

// echo json_encode($data);
echo json_encode($data);
