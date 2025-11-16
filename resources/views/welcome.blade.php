<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ESP32 Monitor</title>
    <style>
        :root {
            --ink: #313647;
            --slate: #435663;
            --sage: #A3B087;
            --sand: #FFF8D4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--ink);
            color: var(--sand);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .shell {
            width: min(1200px, 100%);
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .hero {
            background: linear-gradient(135deg, var(--ink), var(--slate));
            border-radius: 28px;
            padding: clamp(1.5rem, 4vw, 3.5rem);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 2rem;
        }

        .hero h1 {
            font-size: clamp(2rem, 4vw, 3rem);
            margin: 0;
        }

        .hero p {
            margin: 0.75rem 0 0;
            color: rgba(255, 248, 212, 0.8);
            line-height: 1.6;
        }

        .hero-card {
            background: var(--sand);
            color: var(--ink);
            border-radius: 22px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            box-shadow: inset 0 0 0 1px rgba(67, 86, 99, 0.08);
        }

        .hero-card span {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.35em;
            color: rgba(49, 54, 71, 0.55);
        }

        .hero-card strong {
            font-size: clamp(2.5rem, 4vw, 3.5rem);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .card {
            border-radius: 22px;
            padding: 1.75rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }

        .card--temp {
            background: var(--slate);
        }

        .card--hum {
            background: var(--sand);
            color: var(--ink);
        }

        .card--stats {
            background: var(--sage);
            color: var(--ink);
        }

        .card h2 {
            margin: 0;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.35em;
            color: rgba(255, 248, 212, 0.7);
        }

        .card--hum h2,
        .card--stats h2 {
            color: rgba(49, 54, 71, 0.6);
        }

        .value {
            font-size: clamp(2.5rem, 4vw, 3.5rem);
            margin: 1rem 0 0;
            font-weight: 600;
        }

        .sub-text {
            margin: 0.25rem 0 0;
            font-size: 0.85rem;
            color: rgba(255, 248, 212, 0.7);
        }

        .card--hum .sub-text,
        .card--stats .sub-text {
            color: rgba(49, 54, 71, 0.65);
        }

        .bar {
            height: 8px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            margin-top: 1rem;
        }

        .bar-fill {
            height: 100%;
            border-radius: inherit;
            background: var(--sage);
            width: 0;
        }

        .recent {
            background: rgba(255, 248, 212, 0.08);
            border-radius: 26px;
            padding: 2rem;
            border: 1px solid rgba(255, 248, 212, 0.1);
        }

        .recent table {
            width: 100%;
            border-collapse: collapse;
            color: var(--sand);
        }

        .recent th {
            text-align: left;
            font-size: 0.75rem;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: rgba(255, 248, 212, 0.55);
            padding-bottom: 0.75rem;
        }

        .recent td {
            padding: 0.85rem 0;
            border-top: 1px solid rgba(255, 248, 212, 0.1);
        }

        .cta {
            margin-top: 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 1.5rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 248, 212, 0.4);
            color: var(--sand);
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 640px) {
            body {
                padding: 1.25rem;
            }

            .hero-card span {
                letter-spacing: 0.2em;
            }
        }
    </style>
</head>

<body>
    <div class="shell">
        <section class="hero">
            <div>
                <p
                    style="letter-spacing:0.35em;text-transform:uppercase;font-size:0.8rem;color:rgba(255,248,212,0.65);margin:0 0 1rem;">
                    ESP32 ENVIRONMENT MONITOR</p>
                <h1>Realtime Temperature & Humidity</h1>
                <p>Memantau suhu & kelembapan secara realtime dengan pembaruan otomatis setiap menit. Semua data
                    langsung tersimpan dan siap dipantau melalui panel Filament.</p>
                <a class="cta" href="/admin">
                    Buka Dashboard Admin
                    <span aria-hidden="true">→</span>
                </a>
            </div>
            <div class="hero-card">
                <span>Latest Reading</span>
                <strong>{{ isset($stats['latest_temp']) ? number_format($stats['latest_temp'], 1) . '°C' : '—' }}</strong>
                <p class="sub-text" style="color:rgba(49,54,71,0.6);margin:0;">Humidity
                    {{ isset($stats['latest_hum']) ? number_format($stats['latest_hum'], 1) . '%' : '—' }}</p>
                <p class="sub-text" style="color:rgba(49,54,71,0.6);margin:0;">
                    {{ $stats['latest_recorded_at'] ?? 'Belum ada data' }}</p>
            </div>
        </section>

        @php
            $tempPercent = isset($stats['latest_temp']) ? (min(max($stats['latest_temp'], 0), 40) / 40) * 100 : 0;
            $humPercent = isset($stats['latest_hum']) ? min(max($stats['latest_hum'], 0), 100) : 0;
        @endphp

        <section class="grid">
            <article class="card card--temp">
                <h2>Temperature</h2>
                <p class="value">
                    {{ isset($stats['latest_temp']) ? number_format($stats['latest_temp'], 1) . '°C' : '—' }}</p>
                <p class="sub-text">Rata-rata
                    {{ isset($stats['avg_temp']) ? number_format($stats['avg_temp'], 1) . '°C' : '—' }}</p>
                <div class="bar">
                    <div class="bar-fill" style="background:var(--sage);width:{{ $tempPercent }}%"></div>
                </div>
            </article>
            <article class="card card--hum">
                <h2>Humidity</h2>
                <p class="value">{{ isset($stats['latest_hum']) ? number_format($stats['latest_hum'], 1) . '%' : '—' }}
                </p>
                <p class="sub-text">Rata-rata
                    {{ isset($stats['avg_hum']) ? number_format($stats['avg_hum'], 1) . '%' : '—' }}</p>
                <div class="bar" style="background:rgba(67,86,99,0.2);">
                    <div class="bar-fill" style="background:var(--slate);width:{{ $humPercent }}%"></div>
                </div>
            </article>
            <article class="card card--stats">
                <h2>Data Insight</h2>
                <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;margin-top:1rem;">
                    <div>
                        <p class="sub-text" style="color:rgba(49,54,71,0.7);margin:0;">Maximum</p>
                        <p class="value" style="font-size:2rem;margin:0.4rem 0 0;">
                            {{ isset($stats['max_temp']) ? number_format($stats['max_temp'], 1) . '°C' : '—' }}</p>
                    </div>
                    <div>
                        <p class="sub-text" style="color:rgba(49,54,71,0.7);margin:0;">Minimum</p>
                        <p class="value" style="font-size:2rem;margin:0.4rem 0 0;">
                            {{ isset($stats['min_temp']) ? number_format($stats['min_temp'], 1) . '°C' : '—' }}</p>
                    </div>
                </div>
                <p class="sub-text" style="margin-top:1rem;">Total dataset
                    {{ number_format($stats['count_data'] ?? 0) }}</p>
            </article>
        </section>

        <section class="recent">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
                <div>
                    <p
                        style="text-transform:uppercase;letter-spacing:0.35em;font-size:0.75rem;color:rgba(255,248,212,0.6);margin:0;">
                        Recent Records</p>
                    <h3 style="margin:0.5rem 0 0;font-size:1.5rem;">Riwayat Terbaru</h3>
                </div>
                <span
                    style="font-size:0.85rem;color:rgba(255,248,212,0.75);display:flex;align-items:center;gap:0.35rem;">
                    <span
                        style="width:8px;height:8px;border-radius:999px;background:var(--sage);display:inline-block;animation: pulse 1.5s infinite;"></span>
                    Auto refresh 1 menit
                </span>
            </div>

            <div style="overflow-x:auto;margin-top:1.5rem;">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Device</th>
                            <th>Suhu</th>
                            <th>Kelembapan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentReadings as $reading)
                            <tr>
                                <td>{{ optional($reading->created_at)->format('d M Y H:i') ?? '—' }}</td>
                                <td>{{ $reading->device_id ?? 'esp32' }}</td>
                                <td>{{ number_format($reading->temperature ?? 0, 1) }}°C</td>
                                <td>{{ number_format($reading->humidity ?? 0, 1) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    style="text-align:center;padding:1.5rem 0;color:rgba(255,248,212,0.6);">Belum ada
                                    data sensor masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        setInterval(() => {
            window.location.reload();
        }, 60000);
    </script>
</body>

</html>
