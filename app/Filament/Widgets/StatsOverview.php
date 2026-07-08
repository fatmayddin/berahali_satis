<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $paidStatuses = ['paid', 'preparing', 'shipped', 'delivered'];

        return [
            Stat::make('Toplam Ciro', number_format((float) Order::whereIn('status', $paidStatuses)->sum('total'), 2, ',', '.').' ₺')
                ->description('Ödemesi alınan siparişler')
                ->color('success'),

            Stat::make('Sipariş Sayısı', Order::whereIn('status', $paidStatuses)->count())
                ->description(Order::where('status', 'paid')->count().' sipariş hazırlanmayı bekliyor')
                ->color('info'),

            Stat::make('Yayındaki Ürün', Product::where('is_active', true)->count())
                ->description(Product::where('is_active', true)->where('stock', '<', 1)->count().' ürün stokta yok')
                ->color('warning'),

            Stat::make('Okunmamış Mesaj', ContactMessage::where('is_read', false)->count())
                ->color('danger'),
        ];
    }
}
