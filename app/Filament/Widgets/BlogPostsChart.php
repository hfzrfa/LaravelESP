<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class BlogPostsChart extends ChartWidget
{
    protected ?string $heading = 'Temperature and Humidity Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Temperature (°C)',
                    'data' => [22, 24, 23, 25, 26, 24, 27],
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Humidity (%)',
                    'data' => [55, 60, 58, 62, 65, 63, 66],
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'options' => [
                'scales' => [
                    'y' => [
                        'type' => 'linear',
                        'position' => 'left',
                        'title' => [
                            'display' => true,
                            'text' => 'Temperature (°C)',
                        ],
                    ],
                    'y1' => [
                        'type' => 'linear',
                        'position' => 'right',
                        'title' => [
                            'display' => true,
                            'text' => 'Humidity (%)',
                        ],
                        'grid' => [
                            'drawOnChartArea' => false,
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
