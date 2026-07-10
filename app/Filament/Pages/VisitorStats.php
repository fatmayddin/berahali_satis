<?php

namespace App\Filament\Pages;

use App\Models\Visit;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class VisitorStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Ziyaretçiler';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $title = 'Ziyaretçi İstatistikleri';
    protected static ?int $navigationSort = -1;

    protected static string $view = 'filament.pages.visitor-stats';

    public function getViewData(): array
    {
        $today = now()->toDateString();

        return [
            'todayVisitors' => Visit::where('date', $today)->distinct('ip')->count('ip'),
            'todayClicks' => (int) Visit::where('date', $today)->sum('hits'),
            'weekVisitors' => Visit::where('date', '>=', now()->subDays(6)->toDateString())->distinct('ip')->count('ip'),
            'weekClicks' => (int) Visit::where('date', '>=', now()->subDays(6)->toDateString())->sum('hits'),
            'totalVisitors' => Visit::distinct('ip')->count('ip'),
            'totalClicks' => (int) Visit::sum('hits'),

            // Son 14 gün, gün gün
            'daily' => Visit::query()
                ->where('date', '>=', now()->subDays(13)->toDateString())
                ->groupBy('date')
                ->orderByDesc('date')
                ->get(['date', DB::raw('COUNT(DISTINCT ip) as visitors'), DB::raw('SUM(hits) as clicks')]),

            // Son 30 günün en çok gezilen sayfaları
            'topPages' => Visit::query()
                ->where('date', '>=', now()->subDays(29)->toDateString())
                ->groupBy('path')
                ->orderByDesc(DB::raw('SUM(hits)'))
                ->limit(15)
                ->get(['path', DB::raw('COUNT(DISTINCT ip) as visitors'), DB::raw('SUM(hits) as clicks')]),
        ];
    }
}
