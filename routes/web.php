<?php

use App\Models\DhtReading;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $latest = DhtReading::latest()->first();
    $recent = DhtReading::latest()->take(6)->get(['device_id', 'temperature', 'humidity', 'created_at']);

    $stats = [
        'latest_temp' => $latest?->temperature,
        'latest_hum' => $latest?->humidity,
        'latest_recorded_at' => optional($latest?->created_at)?->diffForHumans(),
        'avg_temp' => DhtReading::avg('temperature'),
        'avg_hum' => DhtReading::avg('humidity'),
        'max_temp' => DhtReading::max('temperature'),
        'min_temp' => DhtReading::min('temperature'),
        'count_data' => DhtReading::count(),
    ];

    return view('welcome', [
        'stats' => $stats,
        'recentReadings' => $recent,
    ]);
});
