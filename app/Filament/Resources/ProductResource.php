<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Ürünler';
    protected static ?string $navigationGroup = 'Mağaza';
    protected static ?string $modelLabel = 'Ürün';
    protected static ?string $pluralModelLabel = 'Ürünler';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Genel Bilgiler')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Ürün Adı')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug($state ?? '')) : null),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug (URL)')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Ürünün adres satırında görünecek hali.'),

                    Forms\Components\TextInput::make('code')
                        ->label('Ürün Kodu')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true),

                    Forms\Components\Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Kategori Adı')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            Forms\Components\TextInput::make('slug')->required(),
                        ])
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('stock')
                        ->label('Stok Adedi')
                        ->numeric()
                        ->default(1)
                        ->minValue(0)
                        ->required(),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Yayında')
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Öne Çıkan (Anasayfa)'),
                        Forms\Components\Toggle::make('is_campaign')
                            ->label('Fırsat Ürünü (Fırsatlar sayfası)'),
                    ]),
                ]),

            Forms\Components\Section::make('Kesme Halı')
                ->description('Sabit enli, müşterinin boyunu seçtiği ürünler (yolluk vb.). Fiyat, seçilen boya göre m² üzerinden otomatik hesaplanır.')
                ->schema([
                    Forms\Components\Toggle::make('is_cut')
                        ->label('Bu ürün kesme halı')
                        ->live(),

                    Forms\Components\Grid::make(3)
                        ->visible(fn (Get $get) => (bool) $get('is_cut'))
                        ->schema([
                            Forms\Components\TextInput::make('cut_width_cm')
                                ->label('En (cm, sabit)')
                                ->numeric()
                                ->step('0.1')
                                ->required(fn (Get $get) => (bool) $get('is_cut')),
                            Forms\Components\TextInput::make('cut_min_cm')
                                ->label('Minimum Boy (cm)')
                                ->numeric()
                                ->default(100)
                                ->required(fn (Get $get) => (bool) $get('is_cut')),
                            Forms\Components\TextInput::make('cut_max_cm')
                                ->label('Maksimum Boy (cm)')
                                ->numeric()
                                ->default(1500)
                                ->required(fn (Get $get) => (bool) $get('is_cut')),
                        ]),
                ]),

            Forms\Components\Section::make('Ölçü ve Fiyat')
                ->description('m² ve m² fiyatı girildiğinde toplam fiyat otomatik hesaplanır, isterseniz elle değiştirebilirsiniz.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('size_text')
                        ->label('Ölçü (örn: 170x240 cm)')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('m2')
                        ->label('Metrekare (m²)')
                        ->numeric()
                        ->step('0.01')
                        ->live(onBlur: true)
                        ->visible(fn (Get $get) => ! $get('is_cut'))
                        ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateTotal($set, $get)),

                    Forms\Components\TextInput::make('price_per_m2')
                        ->label('m² Başına Fiyat (₺)')
                        ->numeric()
                        ->step('0.01')
                        ->live(onBlur: true)
                        ->required(fn (Get $get) => (bool) $get('is_cut'))
                        ->helperText(fn (Get $get) => $get('is_cut') ? 'Kesme halıda fiyat bu değer üzerinden hesaplanır.' : null)
                        ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateTotal($set, $get)),

                    Forms\Components\TextInput::make('total_price')
                        ->label('Toplam Fiyat (₺)')
                        ->numeric()
                        ->step('0.01')
                        ->visible(fn (Get $get) => ! $get('is_cut'))
                        ->required(fn (Get $get) => ! $get('is_cut')),

                    Forms\Components\TextInput::make('discount_price')
                        ->label('İndirimli Fiyat (₺)')
                        ->numeric()
                        ->step('0.01')
                        ->visible(fn (Get $get) => ! $get('is_cut'))
                        ->helperText('Boş bırakılırsa indirim uygulanmaz.'),
                ]),

            Forms\Components\Section::make('Görseller')
                ->schema([
                    Forms\Components\FileUpload::make('cover_image')
                        ->label('Kapak Görseli')
                        ->image()
                        ->imageEditor()
                        ->directory('urunler')
                        ->maxSize(5120),

                    Forms\Components\Repeater::make('images')
                        ->label('Galeri')
                        ->relationship()
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->label('Görsel')
                                ->image()
                                ->directory('urunler/galeri')
                                ->maxSize(5120)
                                ->required(),
                        ])
                        ->orderColumn('sort')
                        ->reorderable()
                        ->addActionLabel('Görsel Ekle')
                        ->defaultItems(0)
                        ->grid(3),
                ]),

            Forms\Components\Section::make('Açıklama ve Özellikler')
                ->schema([
                    Forms\Components\RichEditor::make('description')
                        ->label('Açıklama')
                        ->columnSpanFull(),

                    Forms\Components\Repeater::make('features')
                        ->label('Ürün Özellikleri')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Özellik')
                                ->required(),
                            Forms\Components\TextInput::make('value')
                                ->label('Değer')
                                ->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Özellik Ekle')
                        ->defaultItems(0),
                ]),
        ]);
    }

    protected static function calculateTotal(Set $set, Get $get): void
    {
        $m2 = (float) $get('m2');
        $ppm2 = (float) $get('price_per_m2');

        if ($m2 > 0 && $ppm2 > 0) {
            $set('total_price', round($m2 * $ppm2, 2));
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Görsel')
                    ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('m2')
                    ->label('m²')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_price')
                    ->label('İndirimli')
                    ->money('TRY')
                    ->placeholder('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Yayında'),
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Öne Çıkan'),
                Tables\Columns\ToggleColumn::make('is_campaign')
                    ->label('Fırsat'),
                Tables\Columns\IconColumn::make('is_cut')
                    ->label('Kesme')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Yayın Durumu'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
