<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Ayarları';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $title = 'Site Ayarları';

    protected static string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    protected array $keys = [
        'site_title', 'phone', 'email', 'address', 'instagram', 'whatsapp',
        'shipping_cost', 'free_shipping_limit', 'overlock_price', 'about_content',
        'home_headline', 'home_subline',
        'home_feature_1_title', 'home_feature_1_text',
        'home_feature_2_title', 'home_feature_2_text',
        'home_feature_3_title', 'home_feature_3_text',
    ];

    public function mount(): void
    {
        $values = [];
        foreach ($this->keys as $key) {
            $values[$key] = Setting::get($key);
        }

        $this->form->fill($values);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Genel')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('site_title')->label('Site Başlığı'),
                        Forms\Components\TextInput::make('phone')->label('Telefon'),
                        Forms\Components\TextInput::make('email')->label('E-posta'),
                        Forms\Components\TextInput::make('instagram')->label('Instagram Linki'),
                        Forms\Components\TextInput::make('whatsapp')->label('WhatsApp Numarası (905xxxxxxxxx)'),
                        Forms\Components\Textarea::make('address')->label('Adres')->rows(2),
                    ]),

                Forms\Components\Section::make('Kargo & Hizmet')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Kargo Ücreti (₺)')
                            ->numeric(),
                        Forms\Components\TextInput::make('free_shipping_limit')
                            ->label('Ücretsiz Kargo Limiti (₺)')
                            ->numeric()
                            ->helperText('0 girilirse ücretsiz kargo limiti uygulanmaz.'),
                        Forms\Components\TextInput::make('overlock_price')
                            ->label('Overlok Ücreti (₺)')
                            ->numeric()
                            ->helperText('Kesme halılarda overlok seçeneği için adet başına eklenen ücret.'),
                    ]),

                Forms\Components\Section::make('Anasayfa')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('home_headline')->label('Ana Başlık')->columnSpanFull(),
                        Forms\Components\Textarea::make('home_subline')->label('Alt Açıklama')->rows(2)->columnSpanFull(),
                        Forms\Components\TextInput::make('home_feature_1_title')->label('Özellik 1 - Başlık'),
                        Forms\Components\TextInput::make('home_feature_1_text')->label('Özellik 1 - Açıklama'),
                        Forms\Components\TextInput::make('home_feature_2_title')->label('Özellik 2 - Başlık'),
                        Forms\Components\TextInput::make('home_feature_2_text')->label('Özellik 2 - Açıklama'),
                        Forms\Components\TextInput::make('home_feature_3_title')->label('Özellik 3 - Başlık'),
                        Forms\Components\TextInput::make('home_feature_3_text')->label('Özellik 3 - Açıklama'),
                    ]),

                Forms\Components\Section::make('Hakkımızda')
                    ->schema([
                        Forms\Components\RichEditor::make('about_content')->label('Hakkımızda İçeriği'),
                    ]),
            ]);
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Ayarlar kaydedildi')
            ->success()
            ->send();
    }
}
