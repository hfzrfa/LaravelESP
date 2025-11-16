<?php

namespace App\Filament\Pages;

use App\Models\DhtReading;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Widgets\TemperatureChart;

class IotDashboard extends Page
{
    protected static ?string $title = 'IoT Dashboard';
    protected string $view = 'filament.pages.iot-dashboard';

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-chart-bar';
    }

    public function getData(): array
    {
        $latest = DhtReading::latest()->first();
        $recentReadings = DhtReading::latest()->take(8)->get(['device_id', 'temperature', 'humidity', 'created_at']);

        return [
            'latest_temp' => $latest?->temperature ?? null,
            'latest_hum'  => $latest?->humidity ?? null,
            'latest_recorded_at' => optional($latest?->created_at)?->diffForHumans(),
            'avg_temp'    => DhtReading::avg('temperature'),
            'avg_hum'     => DhtReading::avg('humidity'),
            'max_temp'    => DhtReading::max('temperature'),
            'min_temp'    => DhtReading::min('temperature'),
            'count_data'  => DhtReading::count(),
            'recent'      => $recentReadings,
        ];
    }
}
