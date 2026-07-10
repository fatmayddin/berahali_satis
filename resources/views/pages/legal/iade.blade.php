@extends('layouts.app')

@section('title', $title.' - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    @include('pages.legal.partials.header')

    <div class="max-w-3xl mx-auto px-5">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-8 md:p-10 leading-relaxed text-secondary/70 text-[15px] space-y-5">

            <div>
                <h2 class="font-semibold text-secondary mb-2">İade Koşulları</h2>
                <p>Satın aldığınız ürünü, size tesliminden itibaren <strong>14 gün</strong> içinde gerekçe göstermeksizin iade edebilirsiniz. İade edilecek ürünün kullanılmamış, yıkanmamış, hasar görmemiş ve yeniden satılabilir durumda olması; varsa etiket ve ambalajıyla birlikte gönderilmesi gerekir.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">İade Edilemeyen Ürünler</h2>
                <p>Ölçünüze özel olarak kesilen <strong>kesme halı ve yolluklar</strong> ile <strong>overlok uygulanmış ürünler</strong>, kişiye özel üretim kapsamında olduğundan üretim/ürün hatası dışında iade edilemez.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">İade Süreci</h2>
                <p>1. Bize telefon, e-posta veya WhatsApp üzerinden sipariş numaranızla birlikte iade talebinizi iletin.<br>
                2. Size bildireceğimiz anlaşmalı kargo firması ile ürünü ücretsiz gönderin (farklı firma ile gönderimlerde kargo ücreti alıcıya aittir).<br>
                3. Ürün tarafımıza ulaşıp kontrol edildikten sonra bedel iadesi, ödeme yaptığınız karta <strong>iyzico üzerinden 3-14 iş günü</strong> içinde yapılır.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Değişim</h2>
                <p>Aynı ürünün farklı ölçüsü/rengi ile değişim yapmak isterseniz stok durumuna göre yardımcı olmaktan memnuniyet duyarız; bizimle iletişime geçmeniz yeterli.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Hasarlı Ürün</h2>
                <p>Kargo tesliminde paketi kontrol etmenizi öneririz. Hasarlı paketi teslim almayarak kargo görevlisine tutanak tutturun ve bize bildirin; yeni ürününüz en kısa sürede gönderilir.</p>
            </div>
        </div>
    </div>
@endsection
