@extends('layouts.app')

@section('title', $title.' - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    @include('pages.legal.partials.header')

    @php
        $firma = \App\Models\Setting::get('site_title', 'Bera Halı');
        $kargo = number_format((float) \App\Models\Setting::get('shipping_cost', 0), 2, ',', '.');
        $limit = (float) \App\Models\Setting::get('free_shipping_limit', 0);
        $aynigun = number_format((float) \App\Models\Setting::get('same_day_price', 0), 2, ',', '.');
    @endphp

    <div class="max-w-3xl mx-auto px-5">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-8 md:p-10 leading-relaxed text-secondary/70 text-[15px] space-y-5">

            <div>
                <h2 class="font-semibold text-secondary mb-2">Satıcı Bilgileri</h2>
                <p>{{ $firma }}<br>
                Adres: {{ \App\Models\Setting::get('address') }}<br>
                Telefon: {{ \App\Models\Setting::get('phone') }}<br>
                E-posta: {{ \App\Models\Setting::get('email') }}</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Ürün ve Fiyat Bilgisi</h2>
                <p>Satışa sunulan ürünlerin temel nitelikleri (ölçü, m², malzeme, desen vb.) ve vergiler dâhil satış fiyatları ilgili ürün sayfalarında yer alır. Kesme halılarda fiyat, seçtiğiniz boy üzerinden m² birim fiyatı ile anlık hesaplanır; overlok tercih edilmesi halinde overlok ücreti fiyata eklenir. İlan edilen fiyatlar güncelleme yapılana kadar geçerlidir.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Ödeme</h2>
                <p>Ödemeler, iyzico güvenli ödeme altyapısı üzerinden kredi/banka kartıyla, tek çekim veya bankanızın sunduğu taksit seçenekleriyle yapılır. Kart bilgileriniz sitemizde saklanmaz.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Teslimat ve Masraflar</h2>
                <p>Standart kargo ücreti {{ $kargo }} ₺'dir{{ $limit > 0 ? '; '.number_format($limit, 2, ',', '.').' ₺ ve üzeri siparişlerde kargo ücretsizdir' : '' }}. Yakın bölgeler için mağaza aracıyla aynı gün teslimat seçeneği ({{ $aynigun }} ₺) sunulmaktadır. Siparişler ödeme onayından sonra genellikle 1-3 iş günü içinde kargoya verilir; yasal azami teslim süresi 30 gündür.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Cayma Hakkı</h2>
                <p>Ürünün tesliminden itibaren 14 gün içinde cayma hakkınız bulunmaktadır. Ölçüye özel kesilen/kişiselleştirilen ürünlerde (kesme halı, overloklu ürünler) üretim hatası dışında cayma hakkı kullanılamaz. Ayrıntılar için <a href="{{ route('legal', 'mesafeli-satis-sozlesmesi') }}" class="text-primary-600 hover:underline">Mesafeli Satış Sözleşmesi</a>'ni inceleyiniz.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Şikâyet ve Başvuru</h2>
                <p>Şikâyet ve talepleriniz için yukarıdaki iletişim kanallarını kullanabilir; uyuşmazlık halinde Tüketici Hakem Heyetleri ve Tüketici Mahkemelerine başvurabilirsiniz.</p>
            </div>
        </div>
    </div>
@endsection
