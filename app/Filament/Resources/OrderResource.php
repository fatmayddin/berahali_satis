<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Siparişler';
    protected static ?string $navigationGroup = 'Mağaza';
    protected static ?string $modelLabel = 'Sipariş';
    protected static ?string $pluralModelLabel = 'Siparişler';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'paid')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Sipariş Durumu')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Durum')
                        ->options(Order::STATUSES)
                        ->live()
                        ->required(),
                    Forms\Components\Textarea::make('note')
                        ->label('Sipariş Notu')
                        ->rows(2),
                ]),

            Forms\Components\Section::make('Kargo Bilgileri')
                ->description('Kargoya verildiğinde doldurun; müşteri sipariş takibinde görür ve e-posta ile bilgilendirilir.')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('cargo_company')
                        ->label('Kargo Firması')
                        ->options([
                            'Yurtiçi Kargo' => 'Yurtiçi Kargo',
                            'Aras Kargo' => 'Aras Kargo',
                            'MNG Kargo' => 'MNG Kargo',
                            'PTT Kargo' => 'PTT Kargo',
                            'Sürat Kargo' => 'Sürat Kargo',
                            'Trendyol Express' => 'Trendyol Express',
                            'HepsiJet' => 'HepsiJet',
                            'Mağaza Aracı' => 'Mağaza Aracı (Elden Teslim)',
                        ])
                        ->searchable(),
                    Forms\Components\TextInput::make('tracking_number')
                        ->label('Kargo Takip No')
                        ->maxLength(100),
                ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Sipariş Bilgileri')
                ->columns(3)
                ->schema([
                    Infolists\Components\TextEntry::make('order_number')->label('Sipariş No'),
                    Infolists\Components\TextEntry::make('status_label')->label('Durum')->badge()
                        ->color(fn ($record) => match ($record->status) {
                            'paid' => 'success',
                            'pending' => 'warning',
                            'failed', 'cancelled' => 'danger',
                            'shipped', 'delivered' => 'info',
                            default => 'gray',
                        }),
                    Infolists\Components\TextEntry::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i'),
                    Infolists\Components\TextEntry::make('payment_id')->label('İyzico Ödeme No')->placeholder('-'),
                    Infolists\Components\TextEntry::make('paid_at')->label('Ödeme Tarihi')->dateTime('d.m.Y H:i')->placeholder('-'),
                    Infolists\Components\TextEntry::make('payment_error')->label('Ödeme Hatası')->placeholder('-'),
                ]),

            Infolists\Components\Section::make('Müşteri')
                ->columns(3)
                ->schema([
                    Infolists\Components\TextEntry::make('name')->label('Ad Soyad'),
                    Infolists\Components\TextEntry::make('email')->label('E-posta'),
                    Infolists\Components\TextEntry::make('phone')->label('Telefon'),
                    Infolists\Components\TextEntry::make('city')->label('İl'),
                    Infolists\Components\TextEntry::make('district')->label('İlçe')->placeholder('-'),
                    Infolists\Components\TextEntry::make('address')->label('Adres')->columnSpanFull(),
                    Infolists\Components\TextEntry::make('shipping_method_label')
                        ->label('Teslimat Yöntemi')
                        ->badge()
                        ->color(fn ($record) => $record->shipping_method === 'same_day' ? 'warning' : 'gray'),
                    Infolists\Components\TextEntry::make('cargo_company')
                        ->label('Kargo Firması')
                        ->placeholder('-'),
                    Infolists\Components\TextEntry::make('tracking_number')
                        ->label('Kargo Takip No')
                        ->placeholder('-')
                        ->copyable(),
                    Infolists\Components\TextEntry::make('note')->label('Not')->placeholder('-')->columnSpanFull(),
                ]),

            Infolists\Components\Section::make('Ürünler')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('items')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('product_name')->label('Ürün'),
                            Infolists\Components\TextEntry::make('product_code')->label('Kod'),
                            Infolists\Components\TextEntry::make('quantity')->label('Adet'),
                            Infolists\Components\TextEntry::make('unit_price')->label('Birim Fiyat')->money('TRY'),
                            Infolists\Components\TextEntry::make('line_total')->label('Tutar')->money('TRY'),
                        ])
                        ->columns(5),
                ]),

            Infolists\Components\Section::make('Tutar')
                ->columns(3)
                ->schema([
                    Infolists\Components\TextEntry::make('subtotal')->label('Ara Toplam')->money('TRY'),
                    Infolists\Components\TextEntry::make('shipping_cost')->label('Kargo')->money('TRY'),
                    Infolists\Components\TextEntry::make('total')->label('Genel Toplam')->money('TRY')->weight('bold'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Sipariş No')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Müşteri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Tutar')
                    ->money('TRY')
                    ->sortable(),
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
                Tables\Columns\IconColumn::make('odeme_onayi')
                    ->label('İyzico Onayı')
                    ->state(fn (Order $record) => $record->payment_id !== null)
                    ->boolean()
                    ->tooltip(fn (Order $record) => $record->payment_id
                        ? 'Ödeme No: '.$record->payment_id.($record->paid_at ? ' · '.$record->paid_at->format('d.m.Y H:i') : '')
                        : ($record->payment_error ?: 'Ödeme tamamlanmadı')),
                Tables\Columns\TextColumn::make('payment_id')
                    ->label('İyzico Ödeme No')
                    ->placeholder('-')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Ödeme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('shipping_method')
                    ->label('Teslimat')
                    ->formatStateUsing(fn (string $state) => $state === 'same_day' ? 'Aynı Gün' : 'Kargo')
                    ->badge()
                    ->color(fn (string $state) => $state === 'same_day' ? 'warning' : 'gray')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options(Order::STATUSES),
                Tables\Filters\TernaryFilter::make('payment_id')
                    ->label('İyzico Onayı')
                    ->nullable()
                    ->trueLabel('Onaylananlar')
                    ->falseLabel('Onaylanmayanlar')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('payment_id'),
                        false: fn ($query) => $query->whereNull('payment_id'),
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('kargoya_ver')
                    ->label('Kargoya Ver')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Order $record) => in_array($record->status, ['paid', 'preparing']))
                    ->form([
                        \Filament\Forms\Components\Select::make('cargo_company')
                            ->label('Kargo Firması')
                            ->options([
                                'Yurtiçi Kargo' => 'Yurtiçi Kargo',
                                'Aras Kargo' => 'Aras Kargo',
                                'MNG Kargo' => 'MNG Kargo',
                                'PTT Kargo' => 'PTT Kargo',
                                'Sürat Kargo' => 'Sürat Kargo',
                                'Trendyol Express' => 'Trendyol Express',
                                'HepsiJet' => 'HepsiJet',
                                'Mağaza Aracı' => 'Mağaza Aracı (Elden Teslim)',
                            ])
                            ->searchable()
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('tracking_number')
                            ->label('Kargo Takip No')
                            ->maxLength(100),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update([
                            'status' => 'shipped',
                            'cargo_company' => $data['cargo_company'],
                            'tracking_number' => $data['tracking_number'] ?? null,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Sipariş kargoya verildi olarak işaretlendi')
                            ->body('Müşteriye bilgilendirme e-postası gönderildi.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->label('Güncelle'),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
