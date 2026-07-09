<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin kullanıcı
        User::updateOrCreate(
            ['email' => 'admin@berahali.com'],
            [
                'name' => 'Bera Halı Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Site ayarları
        $settings = [
            'site_title' => 'Bera Halı',
            'phone' => '0500 000 00 00',
            'email' => 'info@berahali.com',
            'address' => 'Adres bilgisi buraya gelecek',
            'instagram' => '',
            'whatsapp' => '',
            'shipping_cost' => '250',
            'free_shipping_limit' => '5000',
            'same_day_price' => '200',
            'overlock_price' => '150',
            'about_content' => "Bera Halı'nın hikâyesi, 1995 yılında kurucumuzun halıcılık mesleğine ilk adımını atmasıyla başladı. Halının dokusunu, desenini ve kalitesini yıllar içinde ustalıkla öğrenen kurucumuz, 2009 yılında bugünkü mağazamızı açtı. O günden beri aynı heyecan ve aynı özenle hizmet veriyoruz.\n\nMağazamızda Artemis, Dolce Vita, Merinos gibi Türkiye'nin önde gelen markalarından el emeği İran halılarına kadar her zevke ve her bütçeye uygun 5.000'den fazla ürün seçeneği bulunuyor. Klasikten moderne, yolluktan salon takımına kadar aradığınız her halıyı tek çatı altında bulabilirsiniz. Standart ölçülerin yanında, istediğiniz boyda kestirebileceğiniz kesme halı ve yolluk seçeneklerimiz de mevcut.\n\nBizim için halı satmak yalnızca bir ticaret değil; evinize girecek bir parçayı birlikte seçmektir. Bu yüzden mağazamıza gelen her müşterimizi misafirimiz olarak ağırlar, doğru halıyı bulana kadar birlikte bakarız. Şimdi bu deneyimi internete taşıdık: mağazamızdaki ürünleri online inceleyebilir, iyzico güvencesiyle ödeyebilir ve kapınıza kadar getirtebilirsiniz.\n\nÇeyrek asrı aşan tecrübemizle, halının en iyisini en doğru fiyata sunmaya devam ediyoruz.",
            'home_headline' => 'Eviniz İçin En Güzel Halılar',
            'home_subline' => 'El dokuması ve makine halılarında geniş ürün yelpazesi, kapınıza kadar teslimat.',
            'home_feature_1_title' => 'Güvenli Ödeme',
            'home_feature_1_text' => 'İyzico altyapısı ile kredi kartına taksitli, güvenli ödeme.',
            'home_feature_2_title' => 'Hızlı Kargo',
            'home_feature_2_text' => 'Siparişleriniz özenle paketlenir, hızlıca kargoya verilir.',
            'home_feature_3_title' => 'Mağaza Güvencesi',
            'home_feature_3_text' => 'Tüm ürünler mağazamızın güvencesi altındadır.',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Kategoriler (Ferhatlar Halı formatında ürün grupları)
        $categories = [
            'Dekoratif Halı Grubu',
            'Klasik Halı Grubu',
            'Sisal Halı Grubu',
            'İpek Halı Grubu',
            'Spor Klasik Grubu',
            'Dijital Baskı Halı Grubu',
            'Bambu Halı Grubu',
            'Çocuk Halı Grubu',
            'Kilim Grubu',
            'Post Grubu',
            'Kesme Halı Grubu',
            'Yün Halı Grubu',
        ];
        foreach ($categories as $i => $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort' => $i, 'is_active' => true]
            );
        }

        // Örnek ürünler (görselsiz - adminden görsel eklenebilir)
        $samples = [
            ['name' => 'Klasik Desen El Dokuması Halı', 'code' => 'BH-001', 'category' => 'klasik-hali-grubu', 'size' => '170x240 cm', 'm2' => 4.08, 'ppm2' => 2500, 'discount' => 8900, 'campaign' => true],
            ['name' => 'Modern Gri Dekoratif Halı', 'code' => 'BH-002', 'category' => 'dekoratif-hali-grubu', 'size' => '160x230 cm', 'm2' => 3.68, 'ppm2' => 1200, 'discount' => null, 'campaign' => false],
            ['name' => 'Antik Desenli Yün Halı', 'code' => 'BH-003', 'category' => 'yun-hali-grubu', 'size' => '80x300 cm', 'm2' => 2.40, 'ppm2' => 950, 'discount' => 1990, 'campaign' => true],
            ['name' => 'Otantik El Dokuma Kilim', 'code' => 'BH-004', 'category' => 'kilim-grubu', 'size' => '120x180 cm', 'm2' => 2.16, 'ppm2' => 1800, 'discount' => null, 'campaign' => false],
        ];

        foreach ($samples as $s) {
            $category = Category::where('slug', $s['category'])->first();
            Product::updateOrCreate(
                ['code' => $s['code']],
                [
                    'name' => $s['name'],
                    'slug' => Str::slug($s['name']),
                    'category_id' => $category?->id,
                    'size_text' => $s['size'],
                    'm2' => $s['m2'],
                    'price_per_m2' => $s['ppm2'],
                    'total_price' => round($s['m2'] * $s['ppm2'], 2),
                    'discount_price' => $s['discount'],
                    'description' => 'Örnek ürün açıklaması. Admin panelinden düzenleyebilirsiniz.',
                    'features' => [
                        ['name' => 'Malzeme', 'value' => 'Yün'],
                        ['name' => 'Menşei', 'value' => 'Türkiye'],
                    ],
                    'stock' => 1,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_campaign' => $s['campaign'],
                ]
            );
        }

        // Örnek kesme halı (müşteri boyu seçer, overlok opsiyonlu)
        Product::updateOrCreate(
            ['code' => 'BH-005'],
            [
                'name' => 'Kesme Yolluk - Bej Klasik',
                'slug' => 'kesme-yolluk-bej-klasik',
                'category_id' => Category::where('slug', 'kesme-hali-grubu')->first()?->id,
                'price_per_m2' => 450,
                'total_price' => null,
                'description' => 'İstediğiniz boyda kesilir. 80 cm sabit en, dilerseniz overlok yaptırabilirsiniz.',
                'features' => [
                    ['name' => 'Malzeme', 'value' => 'Polipropilen'],
                    ['name' => 'Menşei', 'value' => 'Türkiye'],
                ],
                'is_cut' => true,
                'cut_width_cm' => 80,
                'cut_min_cm' => 100,
                'cut_max_cm' => 2000,
                'stock' => 50,
                'is_active' => true,
                'is_featured' => true,
                'is_campaign' => false,
            ]
        );
    }
}
