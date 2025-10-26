<?php
// Kirim perintah ke API untuk mengirim command ke ESP32
if (isset($_GET['lampu_on']) || isset($_GET['lampu_off'])) {
  $DEVICE_ID = "esp32-unit-001";
  $COMMAND   = isset($_GET['lampu_on']) ? "lampu_on" : "lampu_off";
  $url       = "http://localhost/iot_app/api/set_command.php";

  // Buat data JSON untuk dikirim ke API
  $data = [
    'device_id' => $DEVICE_ID,
    'command'   => $COMMAND
  ];

  // Kirim JSON via POST
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

  // Eksekusi perintah
  $result = curl_exec($ch);
  curl_close($ch);

  // Buat response JSON dari hasil perintah
  $response = json_decode($result, true);

  // Cek apakah perintah berhasil
  if (!$response || ($response['status'] ?? '') !== 'ok') {
    http_response_code(500);
    echo json_encode(['error' => 'API request failed', 'response' => $response]);
    exit;
  }

  // Balikin ke halaman awal
  header('Location: ../index.php');
  exit;
}
