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
$sql = "SELECT value, raw_value, recorded_at FROM sensor_data
        WHERE device_id = '$device_id'
        ORDER BY recorded_at DESC
        LIMIT 50";
$result = mysqli_query($koneksi, $sql);

// Proses data ke dalam array assoc
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

// echo json_encode($data);
echo json_encode($data);
