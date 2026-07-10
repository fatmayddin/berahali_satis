<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTabs(): array
    {
        return [
            'tumu' => Tab::make('Tümü')
                ->badge(Order::count()),
            'odenen' => Tab::make('Ödemesi Alınanlar')
                ->badge(Order::whereIn('status', ['paid', 'preparing', 'shipped', 'delivered'])->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', ['paid', 'preparing', 'shipped', 'delivered'])),
            'bekleyen' => Tab::make('Ödeme Bekleyenler')
                ->badge(Order::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),
            'basarisiz' => Tab::make('Başarısız / İptal')
                ->badge(Order::whereIn('status', ['failed', 'cancelled'])->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', ['failed', 'cancelled'])),
        ];
    }
}
