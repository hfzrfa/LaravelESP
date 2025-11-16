#include <WiFi.h>
#include <HTTPClient.h>
#include "DHT.h"

#define DHTPIN 4
#define DHTTYPE DHT11

// ====== WIFI CONFIG ======
const char* ssid     = "05Room";
const char* password = "10042006";

// Ganti 192.168.1.10 dengan IPv4 laptop yang kamu dapat dari ipconfig
const char* serverUrl = "http://192.168.1.104:8000/api/dht";

// harus sama dengan ESP32_SECRET di .env Laravel
const char* secret   = "10042006";
const char* deviceId = "esp32-ruang-utama";

DHT dht(DHTPIN, DHTTYPE);

void setup() {
  Serial.begin(115200);
  delay(1000);

  dht.begin();

  Serial.println();
  Serial.println("Menghubungkan ke WiFi...");
  WiFi.begin(ssid, password);

  int retry = 0;
  while (WiFi.status() != WL_CONNECTED && retry < 30) {
    delay(500);
    Serial.print(".");
    retry++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println();
    Serial.println("WiFi connected");
    Serial.print("IP ESP32: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println();
    Serial.println("Gagal konek WiFi");
  }
}

void loop() {
  float h = dht.readHumidity();
  float t = dht.readTemperature(); // Celsius

  if (isnan(h) || isnan(t)) {
    Serial.println("Gagal baca DHT, skip kirim!");
    delay(5000);
    return;
  }

  Serial.print("Baca DHT => Temp: ");
  Serial.print(t);
  Serial.print(" °C, Humidity: ");
  Serial.print(h);
  Serial.println(" %");

  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");

    String json = "{";
    json += "\"device_id\":\"" + String(deviceId) + "\",";
    json += "\"temperature\":" + String(t, 1) + ",";
    json += "\"humidity\":" + String(h, 1) + ",";
    json += "\"secret\":\"" + String(secret) + "\"";
    json += "}";

    Serial.print("Kirim JSON: ");
    Serial.println(json);

    int httpResponseCode = http.POST(json);

    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);

    if (httpResponseCode > 0) {
      String payload = http.getString();
      Serial.println("Response body:");
      Serial.println(payload);
    } else {
      Serial.print("Error request: ");
      Serial.println(httpResponseCode);
    }

    http.end();
  } else {
    Serial.println("WiFi tidak konek, tidak kirim.");
  }

  // Kirim tiap 10 detik
  delay(100000);
}
