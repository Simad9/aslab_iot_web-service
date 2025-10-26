# IOT APP - Sederhana

---

Repo ini berguna untuk membantu `praktikan` dan `aslab` dalam mengarjakan IoT.\

### Langkah Praktikum Via Tulisan

Link Notion : [Klik Disini](https://www.notion.so/Modul-Prak-IoT-Web-Service-by-API-29750fec77e880a1bb22eb5f43fe7fcc?source=copy_link)

### Technology Stack

- PHP Vanila
- MySQL
- ARDUINO

### API Endpoint

Karena menggunakan PHP Vanila jadinya, menggunakan file

1. Ambil Data Sensor
   `http://[IP Laptop]/iot_app/api/get_sensor_data.php?device_id=esp32-unit-001`
2. Lanjut nanti

### Cara Pake

1. Clone repo ini, pada folder `xampp/htdocs/iot_app`
2. Jalankan Apache dan MySQL
3. Buat database baru di MySQL dengan nama iot_app
4. Import file `iot_app.sql` ke database iot_app
5. Copy Paste file `arduino/esp32.ino` di Arduino Uno, dan sesuaikan inputannya
6. Buka `localhost/iot_app` di browser untuk akses webnya
7. Edit file Arduino Uno nya, mengikuti Hostpot masing masing
8. Lebih lengkap baca Link Notion

---

### Catatan Developer

1. API untuk query ubah jadi yang biasa dulu
2. Isi folder `api_pdo` dengan file api yang querynya pake pdo
3. Export sql
4. Integarasikan ke alatnya
