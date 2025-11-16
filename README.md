# ESP32 Monitor

Modern Laravel + Filament stack for collecting ESP32 DHT readings, storing them securely, and visualising the data on both a public landing page and an admin IoT dashboard. The backend exposes a single authenticated ingestion endpoint (`POST /api/dht`) that devices can call every few seconds or minutes.

## Table of Contents
1. [Features](#features)
2. [Tech Stack](#tech-stack)
3. [Hardware & Wiring](#hardware--wiring)
4. [ESP32 Firmware Outline](#esp32-firmware-outline)
5. [Backend Setup](#backend-setup)
6. [Environment & Configuration](#environment--configuration)
7. [API Reference](#api-reference)
8. [Dashboards](#dashboards)
9. [Testing](#testing)
10. [Troubleshooting](#troubleshooting)

## Features
- **Secure ingestion API** guarded by a shared secret (`ESP32_SECRET`).
- **Filament Admin panel** with IoT Dashboard, temperature chart, humidity timeline, and a log of recent readings.
- **Polished landing page** (`/`) mirroring the dashboard palette (#313647, #435663, #A3B087, #FFF8D4) that auto-refreshes every minute.
- **Realtime feel** via Livewire polling (60 s) and chart widgets (15 s) so data updates without manual refresh.
- **SQLite-friendly tests** thanks to automatic database refreshing (Pest + RefreshDatabase).

## Tech Stack
- **Laravel 11** + Pest for testing
- **Filament 3** for admin resources & custom Pages/Widgets
- **MySQL** (or any Laravel-supported DB) for persistence
- **Vite** + Tailwind for assets (optional for dashboards)
- **ESP32** + DHT11/DHT22 sensor for telemetry input

## Hardware & Wiring

| Component | Pin | Connects To | Notes |
|-----------|-----|-------------|-------|
| ESP32 DevKit | 3V3 | DHT VCC | Sensor powered from 3.3 V |
| ESP32 DevKit | GND | DHT GND | Common ground |
| ESP32 DevKit | GPIO 4 (D4) | DHT DATA | Any digital pin works; adjust firmware |
| 10 kΩ Resistor | Between DATA & 3V3 | Pull-up resistor | Keeps the data line stable |

> Tip: keep the sensor cable short (<20 cm) or use shielded wire to reduce noise. If you deploy outdoors, add a Stevenson screen or weather-proof enclosure.

## ESP32 Firmware Outline

Pseudocode for sending readings every 10 s:

```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

const char* WIFI_SSID = "your-ssid";
const char* WIFI_PASS = "your-pass";
const char* API_URL = "http://your-server/api/dht";
const char* API_SECRET = "your-scret"; // match .env ESP32_SECRET

DHT dht(4, DHT22); // GPIO 4

void setup() {
	WiFi.begin(WIFI_SSID, WIFI_PASS);
	while (WiFi.status() != WL_CONNECTED) { delay(500); }
	dht.begin();
}

void loop() {
	float t = dht.readTemperature();
	float h = dht.readHumidity();
	if (isnan(t) || isnan(h)) return;

	HTTPClient http;
	http.begin(API_URL);
	http.addHeader("Content-Type", "application/json");

	String payload = String("{\"temperature\":") + t + ",\"humidity\":" + h + ",\"secret\":\"" + API_SECRET + "\"}";
	http.POST(payload);
	http.end();

	delay(10000); // 10s interval
}
```

Send any additional metadata (e.g., `device_id`) as part of the JSON body if you have multiple boards.

## Backend Setup

```bash
# Clone and install dependencies
git clone https://github.com/hfzrfa/LaravelESP.git
cd esp32-monitor
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Configure database + secret in .env then run migrations & seeders
php artisan migrate --seed

# Build or watch assets (optional for Filament)
npm run build   # or: npm run dev

# Start local server
php artisan serve
```

The seeder provisions an admin account (`admin@esp32.test` / `password`). Change it immediately in production.

## Environment & Configuration

Key `.env` entries:

| Key | Description |
|-----|-------------|
| `DB_*` | Connection used by Laravel & Filament |
| `ESP32_SECRET` | Shared key the ESP32 must send under `secret` in every POST request |
| `APP_URL` | Base URL used for asset links & API docs |

When rotating `ESP32_SECRET`, update both `.env` and the firmware constant.

## API Reference

`POST /api/dht`

```json
{
	"device_id": "esp32-lab-01",
	"temperature": 24.7,
	"humidity": 61.3,
	"secret": "your-scret"
}
```

Responses:
- `200 OK` → `{ "message": "OK", "id": 123 }`
- `401 Unauthorized` if the secret mismatches
- `422 Unprocessable Entity` for invalid payloads

Quick curl:

```bash
curl -X POST http://localhost:8000/api/dht \
	-H "Content-Type: application/json" \
	-d '{
				"device_id": "workbench-esp32",
				"temperature": 25.4,
				"humidity": 58.1,
				"secret": "your-scret"
			}'
```

## Dashboards

- **Public landing page** (`/`): auto-refreshes every 60 s, shows KPIs, recent readings, and a call-to-action button to jump into Filament.
- **Filament Admin** (`/admin`):
	- IoT Dashboard page with hero stats, recent table, and cards styled with the same palette.
	- Temperature chart widget polling every 10–15 s for near-realtime graphs.
	- DHT Reading resource for CRUD access (currently view-only to keep history immutable).

Suggested screenshots:

```
docs/
 ├─ dashboard.png
 └─ public-landing.png
```

Link them in the README once captured.

## Testing

```bash
php artisan test
```

Feature tests boot a fresh in-memory SQLite database (`RefreshDatabase`) so make sure migrations stay up to date.

## Troubleshooting

- **401 Unauthorized**: confirm the board sends the same `ESP32_SECRET` as the server. Remember to reboot the board after changing the constant.
- **Blank charts**: ensure at least one reading exists (`php artisan tinker --execute "App\\Models\\DhtReading::factory()->create()"`). Charts intentionally cap values at 40 °C and 100 % to keep scales readable.
- **Migrations not running in tests**: run `php artisan migrate:fresh --seed` locally; the Pest suite already refreshes the DB each run.
- **Slow Filament UI**: run `php artisan optimize:clear` after switching branches, and keep `npm run dev`/`build` up to date for asset changes.

Enjoy hacking on your ESP32 monitor! Contributions and improvements (extra widgets, alerts, OTA update helpers, etc.) are welcome.
