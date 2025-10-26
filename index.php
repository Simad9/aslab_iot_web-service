<?php
// Untuk Lampu Nyala dan Mati
if (isset($_GET['lampu_on'])) {
  // CODE KIRIM LAMPU NYALA
} else if (isset($_GET['lampu_off'])) {
  // KIRIM LAMPU MATI
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IoT App</title>
  <!-- CSS BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <h1 class="text-center my-3">IoT App</h1>

    <!-- REMOTE -->
    <section class="mb-3">
      <h2>Remote Control (misalnya)</h2>
      <div class="d-flex gap-3">
        <form action="">
          <button type="submit" class="btn btn-primary" name="lampu_on" value="true">Lampu ON</button>
          <button type="submit" class="btn btn-outline-primary" name="lampu_off" value="true">lampu OFF</button>
        </form>
      </div>
    </section>

    <!-- DATA -->
    <section>
      <h2>Data Sensor</h2>
      <!-- Tabel -->
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Data</th>
            <th scope="col">Tanggal & Waktu</th>
          </tr>
        </thead>
        <tbody>
          <!-- Ngambil Data by API -->
          <?php
          $result = file_get_contents('http://localhost/iot_app/api/get_sensor_data.php?device_id=esp32-unit-001');
          $datas = json_decode($result, true);
          ?>
          <!-- LOOP -->
          <?php foreach ($datas as $key => $data):
          ?>
            <tr>
              <th scope="row"><?= $key + 1 ?></th>
              <td><?= $data['value'] ?></td>
              <td><?= $data['recorded_at'] ?></td>
            </tr>
          <?php endforeach; ?>
          <!-- END LOOP -->
        </tbody>
      </table>
    </section>
  </div>

  <!-- JS BOOTSTRAP -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>