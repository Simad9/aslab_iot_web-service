<?php
header('Content-Type: application/json');

// config.php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'iot_app';

// Kunci API (harus sama di ESP32 dan web)
// $API_KEY = 'S3cretAPIKeyReplaceThis'; // ganti dengan yg kuat & rahasiakan

// Cara 1 = pilih ssalah satu
$koneksi = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
if($koneksi->connect_error) {
  http_response_code(500);
  echo json_encode(['error' => 'DB connection failed']);
  exit;
}

