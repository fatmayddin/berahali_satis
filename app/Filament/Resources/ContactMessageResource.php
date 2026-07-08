<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'İletişim Mesajları';
    protected static ?string $navigationGroup = 'İçerik';
    protected static ?string $modelLabel = 'Mesaj';
    protected static ?string $pluralModelLabel = 'Mesajlar';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('is_read', false)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make()
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('name')->label('Ad Soyad'),
                    Infolists\Components\TextEntry::make('email')->label('E-posta'),
                    Infolists\Components\TextEntry::make('phone')->label('Telefon')->placeholder('-'),
                    Infolists\Components\TextEntry::make('subject')->label('Konu')->placeholder('-'),
                    Infolists\Components\TextEntry::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i'),
                    Infolists\Components\TextEntry::make('message')->label('Mesaj')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Okundu')
                    ->boolean(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Konu')
                    ->limit(40)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')->label('Okundu'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->after(fn (ContactMessage $record) => $record->update(['is_read' => true])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
        ];
    }
}
