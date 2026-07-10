<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Yöneticiler';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $modelLabel = 'Yönetici';
    protected static ?string $pluralModelLabel = 'Yöneticiler';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('is_admin', true);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Ad Soyad')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('E-posta')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('phone')
                ->label('Telefon'),

            Forms\Components\TextInput::make('password')
                ->label('Şifre')
                ->password()
                ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn (?string $state) => filled($state))
                ->required(fn (string $operation) => $operation === 'create')
                ->helperText('Düzenlemede boş bırakılırsa şifre değişmez.'),

            Forms\Components\Toggle::make('is_admin')
                ->label('Yönetici')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->placeholder('-'),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Yönetici')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Kayıt Tarihi')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')->label('Yönetici'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
