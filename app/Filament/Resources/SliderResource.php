<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Slider Yönetimi';
    protected static ?string $navigationGroup = 'İçerik';
    protected static ?string $modelLabel = 'Slider';
    protected static ?string $pluralModelLabel = 'Sliderlar';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('image')
                ->label('Slider Görseli')
                ->image()
                ->imageEditor()
                ->directory('sliders')
                ->maxSize(5120)
                ->required()
                ->helperText('Önerilen boyut: 1920x700 px')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('title')
                ->label('Başlık')
                ->maxLength(255),

            Forms\Components\TextInput::make('subtitle')
                ->label('Alt Başlık')
                ->maxLength(255),

            Forms\Components\TextInput::make('link')
                ->label('Bağlantı (URL)')
                ->maxLength(255)
                ->helperText('Örn: /urunler'),

            Forms\Components\TextInput::make('button_text')
                ->label('Buton Yazısı')
                ->maxLength(100),

            Forms\Components\TextInput::make('sort')
                ->label('Sıra')
                ->numeric()
                ->default(0),

            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Görsel')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort')
                    ->label('Sıra')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
