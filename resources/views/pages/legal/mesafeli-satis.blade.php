@extends('layouts.app')

@section('title', $title.' - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    @include('pages.legal.partials.header')

    @php
        $firma = \App\Models\Setting::get('site_title', 'Bera Halı');
        $adres = \App\Models\Setting::get('address');
        $tel = \App\Models\Setting::get('phone');
        $eposta = \App\Models\Setting::get('email');
    @endphp

    <div class="max-w-3xl mx-auto px-5">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-8 md:p-10 leading-relaxed text-secondary/70 text-[15px] space-y-5">

            <div>
                <h2 class="font-semibold text-secondary mb-2">1. Taraflar</h2>
                <p><strong>Satıcı:</strong> {{ $firma }} — {{ $adres }} — Tel: {{ $tel }} — E-posta: {{ $eposta }}</p>
                <p class="mt-1"><strong>Alıcı:</strong> Sipariş formunda ad, soyad, adres ve iletişim bilgileri yer alan müşteri.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">2. Konu</h2>
                <p>İşbu sözleşmenin konusu, Alıcı'nın Satıcı'ya ait internet sitesinden elektronik ortamda siparişini verdiği, nitelikleri ve satış fiyatı sitede ve sipariş özetinde belirtilen ürünlerin satışı ve teslimi ile ilgili olarak 6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler Yönetmeliği hükümleri gereğince tarafların hak ve yükümlülüklerinin belirlenmesidir.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">3. Sözleşme Konusu Ürün, Ödeme ve Teslimat</h2>
                <p>Ürünlerin cinsi, türü, miktarı, ölçüleri, satış bedeli ve ödeme şekli sipariş sonlandığı andaki sipariş özetinde ve Alıcı'ya gönderilen sipariş onay e-postasında belirtildiği gibidir. Ödemeler iyzico güvenli ödeme altyapısı üzerinden kredi/banka kartı ile tahsil edilir; kart bilgileri Satıcı tarafından saklanmaz.</p>
                <p class="mt-1">Ürünler, seçilen teslimat yöntemine göre anlaşmalı kargo firması ile veya Satıcı'nın kendi aracıyla Alıcı'nın sipariş formunda belirttiği adrese teslim edilir. Teslimat süresi, ödemenin onaylanmasından itibaren en geç 30 gündür; stok durumuna göre siparişler genellikle 1-3 iş günü içinde kargoya verilir.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">4. Cayma Hakkı</h2>
                <p>Alıcı, ürünün kendisine tesliminden itibaren 14 (on dört) gün içinde herhangi bir gerekçe göstermeksizin ve cezai şart ödemeksizin sözleşmeden cayma hakkına sahiptir. Cayma hakkının kullanılması için bu süre içinde Satıcı'ya telefon, e-posta veya WhatsApp yoluyla bildirimde bulunulması gerekir. Cayma halinde ürün, faturası ve tüm aksesuarları ile birlikte, kullanılmamış ve yeniden satılabilir durumda iade edilmelidir.</p>
                <p class="mt-1"><strong>İstisna:</strong> Mesafeli Sözleşmeler Yönetmeliği'nin 15. maddesi uyarınca, Alıcı'nın istekleri doğrultusunda ölçüye göre kesilen/kişiselleştirilen ürünlerde (kesme halı, ölçüye özel yolluk, overlok uygulanmış ürünler) üretim hatası dışında cayma hakkı kullanılamaz.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">5. Genel Hükümler</h2>
                <p>Alıcı, sitede yer alan ürünün temel nitelikleri, satış fiyatı, ödeme şekli ve teslimata ilişkin ön bilgileri okuyup bilgi sahibi olduğunu ve elektronik ortamda gerekli teyidi verdiğini kabul eder. Sipariş onayı ile birlikte işbu sözleşme kurulmuş sayılır. Teslim edilen üründe hasar veya ayıp bulunması halinde Alıcı, ürünü teslim aldığı tarihten itibaren makul süre içinde Satıcı'ya bildirmekle yükümlüdür.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">6. Uyuşmazlıkların Çözümü</h2>
                <p>İşbu sözleşmeden doğan uyuşmazlıklarda, Ticaret Bakanlığı'nca ilan edilen parasal sınırlar dâhilinde Alıcı'nın veya Satıcı'nın yerleşim yerindeki Tüketici Hakem Heyetleri ile Tüketici Mahkemeleri yetkilidir.</p>
            </div>

            <p class="text-tagline-2 text-secondary/40">Bu metin genel bir şablondur; nihai halinin bir hukuk danışmanınca gözden geçirilmesi önerilir.</p>
        </div>
    </div>
@endsection
