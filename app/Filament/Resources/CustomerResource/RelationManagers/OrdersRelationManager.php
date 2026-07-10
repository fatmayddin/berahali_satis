<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Satın Alımları';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Sipariş No')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Tutar')
                    ->money('TRY'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Order::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'cancelled' => 'danger',
                        'shipped', 'delivered' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_id')
                    ->label('İyzico Ödeme No')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('detay')
                    ->label('Sipariş Detayı')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Order $record) => OrderResource::getUrl('view', ['record' => $record])),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
