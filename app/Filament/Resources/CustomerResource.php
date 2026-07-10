<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;
use App\Models\User;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Müşteriler';
    protected static ?string $navigationGroup = 'Mağaza';
    protected static ?string $modelLabel = 'Müşteri';
    protected static ?string $pluralModelLabel = 'Müşteriler';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'musteriler';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_admin', false)
            ->withCount('orders')
            ->withSum(['orders as total_spent' => fn (Builder $q) => $q->whereIn('status', ['paid', 'preparing', 'shipped', 'delivered'])], 'total');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('is_admin', false)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Müşteri Bilgileri')
                ->columns(3)
                ->schema([
                    Infolists\Components\TextEntry::make('name')->label('Ad Soyad'),
                    Infolists\Components\TextEntry::make('email')->label('E-posta'),
                    Infolists\Components\TextEntry::make('phone')->label('Telefon')->placeholder('-'),
                    Infolists\Components\TextEntry::make('created_at')->label('Üyelik Tarihi')->dateTime('d.m.Y H:i'),
                    Infolists\Components\TextEntry::make('orders_count')
                        ->label('Toplam Sipariş')
                        ->state(fn (User $record) => $record->orders()->count()),
                    Infolists\Components\TextEntry::make('total_spent')
                        ->label('Toplam Harcama')
                        ->state(fn (User $record) => number_format((float) $record->orders()->whereIn('status', ['paid', 'preparing', 'shipped', 'delivered'])->sum('total'), 2, ',', '.').' ₺'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Sipariş')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Toplam Harcama')
                    ->money('TRY')
                    ->placeholder('0,00 ₺')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Üyelik Tarihi')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('İncele'),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }
}
