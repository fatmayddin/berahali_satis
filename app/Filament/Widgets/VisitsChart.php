<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VisitsChart extends ChartWidget
{
    protected static ?string $heading = 'Son 30 Gün Ziyaretçi Trafiği';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $rows = Visit::query()
            ->where('date', '>=', now()->subDays(29)->toDateString())
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                'date',
                DB::raw('COUNT(DISTINCT ip) as visitors'),
                DB::raw('SUM(hits) as clicks'),
            ])
            ->keyBy(fn ($row) => $row->date->toDateString());

        $labels = [];
        $visitors = [];
        $clicks = [];

        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $key = $day->toDateString();
            $labels[] = $day->format('d.m');
            $visitors[] = (int) ($rows[$key]->visitors ?? 0);
            $clicks[] = (int) ($rows[$key]->clicks ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tekil Ziyaretçi (IP)',
                    'data' => $visitors,
                    'borderColor' => '#864ffe',
                    'backgroundColor' => 'rgba(134, 79, 254, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Tıklama (Sayfa Görüntüleme)',
                    'data' => $clicks,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.05)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
