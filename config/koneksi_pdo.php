<?php
header('Content-Type: application/json');

// config.php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'iot_app';

// Kunci API (harus sama di ESP32 dan web)
// $API_KEY = 'S3cretAPIKeyReplaceThis'; // ganti dengan yg kuat & rahasiakan

// Cara 2 = pilih ssalah satu
try {
  $koneksi = new PDO(
    "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
    $dbUser,
    $dbPass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'DB connection failed']);
  exit;
}
