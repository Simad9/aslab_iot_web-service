#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>          // Libray DHT Sensor Library by 
#include <ArduinoJson.h>  // Library ArduinoJson

#define DHTPIN 14      // pin DHT Pin - Data Sensor
#define DHTTYPE DHT11  // pin Type DHT11 - Tipe Sensor
DHT dht(DHTPIN, DHTTYPE);

#define LED_BUILTIN 2

// konfigurasi WiFi dan server
const char* ssid = "YOUR_SSID";                       // Nama WiFi Kamu
const char* password = "YOUR_WIFI_PASS";              // Password WiFI Kamu
const char* server = "http://your-domain.or.ip/api";  // IP PC kamu
// const char* API_KEY = "S3cretAPIKeyReplaceThis";   // (opsional) untuk keamanan aja
const char* DEVICE_ID = "esp32-unit-001";             // (opsional) untuk ngasih tau Device aja

// Untuk ngatur waktu
unsigned long lastSend = 0;
const unsigned long sendInterval = 60 * 1000UL; // kirim tiap 60 detik
// const unsigned long sendInterval = 5 * 1000UL; // kirim tiap 5 detik
unsigned long lastPoll = 0;
const unsigned long pollInterval = 10 * 1000UL; // cek perintah tiap 10 detik
// const unsigned long sendInterval = 5 * 1000UL; // cek perintah tiap 5 detik

void setup(){
  // Setup Basic
  Serial.begin(115200);             // Jalanin Serial Monitor
  dht.begin();                      // Jalanin DHT Sensor
  pinMode(LED_BUILTIN, OUTPUT);     // Lampu Builtin ESP32 jadi OUTPUT
  digitalWrite(LED_BUILTIN, LOW);   // default mati

	// Koneksi WiFI
	WiFi.begin(ssid, password);                    // Koneksi ke Hospot kita
	Serial.println("Connecting to WiFi...");       // Ngasih tau kalo lagi konek
	int retries = 0;
	while (WiFi.status() != WL_CONNECTED) {        // Handler untuk status
	  delay(1000);
	  Serial.print("Status: ");
	  Serial.println(WiFi.status());               // Kode Status cek "Contoh Status"
	  retries++;
	  if (retries > 20) {                          // Kalo gak konek konek langsung tampil error
	    Serial.println("Failed to connect to WiFi.");  
	    return; // keluar dari setup
	  }
	}
	Serial.println("Connected!");                  // Berhasil Connect
	Serial.println(WiFi.localIP());                // Nampilin IP Laptop
	// === Contoh Status ===
	// WL_NO_SSID_AVAIL (1) → SSID gak ditemukan
	// WL_CONNECT_FAILED (4) → password salah
	// WL_IDLE_STATUS (0) → belum mulai koneksi
	// WL_DISCONNECTED (6) → koneksi gagal
}

void loop(){
  unsigned long now = millis();
  
  // Ngirim data sesuai sendInterval (berapa waktu yang diset tadi)
  if (now - lastSend >= sendInterval) {
    lastSend = now;
    sendSensorData();
  }
  
  // Nerima status sesuai PollInterval (berapa waktu yang diset tadi)
  if (now - lastPoll >= pollInterval) {
    lastPoll = now;
    pollCommands();
  }
}

// -- Fungsi untuk mengirimkan Data Sensor
void sendSensorData(){
	// Nangkap Data DHT11
  float h = dht.readHumidity();
  float t = dht.readTemperature();
	  
	// Control Handler Data
  if (isnan(h) || isnan(t)) {
    Serial.println("Failed to read from DHT");
    return;
  }
  
  // post JSON ke save_data.php
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    
    // Buat URL tujuan
    String url = String(server) + "/save_data.php";
    http.begin(url);
    
    // Tambahkan header agar server tahu data yang dikirim berupa JSON
    http.addHeader("Content-Type", "application/json");
		
	  // Buat data JSON untuk dikirim
    StaticJsonDocument<256> doc;
    doc["device_id"] = DEVICE_ID;
    doc["sensor_type"] = "DHT11_temp";
    doc["value"] = t;
    doc["raw"] = String("h=") + String(h);
		
		// Ubah JSON menjadi string dan kirim via HTTP POST
    String body;
    serializeJson(doc, body);
    int code = http.POST(body);
		
		// Tampilkan hasil pengiriman (response dari server)
    if (code > 0) {
      String resp = http.getString();
      Serial.printf("sendSensorData code=%d resp=%s\n", code, resp.c_str());
    } else {
      Serial.printf("POST failed, error: %s\n", http.errorToString(code).c_str());
    }
    
    // Tutup koneksi HTTP
    http.end();
  }
}

// -- Fungsi untuk nangkap status dan commands
void pollCommands(){
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    
    // Buat URL tujuan untuk ambil command dari server 
    // (parameter di URL seperti device_id dan api_key bisa dihapus kalau tidak dipakai oleh server)
    // String url = String(server) + "/get_command.php?device_id=" + DEVICE_ID + "&api_key=" + API_KEY; // Kalo mau pake APIKEY
    String url = String(server) + "/get_command.php?device_id=" + DEVICE_ID;
    http.begin(url);
    
    // Kirim HTTP GET request ke server
    int code = http.GET();
    
    // Cek apakah server merespons dengan status OK (HTTP 200)
    if (code == 200) {
      // Ambil isi (payload) dari respons server
      String payload = http.getString();
      Serial.println("pollCommands: " + payload);
      
      // Parse (ubah) data JSON dari string menjadi objek JSON
      DynamicJsonDocument doc(512);
      DeserializationError err = deserializeJson(doc, payload);
      
      /// Jika parsing JSON berhasil dan status dari server "ok"
      if (!err && doc["status"] == String("ok")) {
        long cmd_id = doc["command_id"];
        const char* cmd = doc["command"];
        const char* cmdPayload = doc["payload"];
        
        // Jalankan perintah yang diterima
        executeCommand(cmd_id, String(cmd), String(cmdPayload));
      }
    } else {
      // Tampilkan pesan error jika GET gagal
      Serial.printf("GET failed, code %d\n", code);
    }
    
    // Tutup koneksi HTTP setelah selesai
    http.end();
  }
}

// -- Fungsi untuk eksekusi command dari status
void executeCommand(long id, String cmd, String payload){
	// Nampilin command apa yang akan dieksekusi
  Serial.printf("Exec command id=%ld cmd=%s payload=%s\n", id, cmd.c_str(), payload.c_str());
  String result = "unknown";
  
  // Percabangan untuk menjalankan IoT by command / status
  if (cmd == "lampu_on") {
    digitalWrite(LED_BUILTIN, HIGH);
    result = "lampu_on_ok";
  } else if (cmd == "lampu_off") {
    digitalWrite(LED_BUILTIN, LOW);
    result = "lampu_off_ok";
  } else {
    result = "cmd_not_supported";
  }

  // ack ke server - update data kalo udah di eksekusi
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    
    // Buat URL tujuan
    String url = String(server) + "/ack_command.php";
    http.begin(url);
    
    // Tambahkan header agar server tahu data yang dikirim berupa JSON
    http.addHeader("Content-Type", "application/json");

		// Buat data JSON untuk dikirim
    StaticJsonDocument<256> doc;
    doc["api_key"] = API_KEY;
    doc["command_id"] = id;
    doc["result"] = result;

		// Ubah JSON menjadi string dan kirim via HTTP POST
    String body;
    serializeJson(doc, body);
    int code = http.POST(body);
    
    // Tampilkan hasil pengiriman (response dari server)
    if (code > 0) {
      Serial.printf("ack code=%d resp=%s\n", code, http.getString().c_str());
    }
    
    
    http.end();
  }