@extends('layouts.app')

@section('title', $title.' - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    @include('pages.legal.partials.header')

    @php $firma = \App\Models\Setting::get('site_title', 'Bera Halı'); @endphp

    <div class="max-w-3xl mx-auto px-5">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-8 md:p-10 leading-relaxed text-secondary/70 text-[15px] space-y-5">

            <div>
                <h2 class="font-semibold text-secondary mb-2">Veri Sorumlusu</h2>
                <p>6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca kişisel verileriniz, veri sorumlusu sıfatıyla {{ $firma }} tarafından aşağıda açıklanan kapsamda işlenmektedir.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">İşlenen Veriler ve Amaçları</h2>
                <p><strong>Kimlik ve iletişim bilgileri</strong> (ad soyad, telefon, e-posta, teslimat adresi): siparişlerin oluşturulması, teslimatı ve müşteri iletişimi için.<br>
                <strong>İşlem güvenliği bilgileri</strong> (IP adresi, ziyaret edilen sayfalar): site güvenliği ve ziyaretçi istatistikleri için.<br>
                <strong>Ödeme bilgileri:</strong> kart bilgileriniz sitemizde saklanmaz; ödemeler iyzico Ödeme Hizmetleri A.Ş. altyapısında gerçekleşir.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Aktarım</h2>
                <p>Verileriniz yalnızca hizmetin gerektirdiği ölçüde; ödeme işlemleri için iyzico'ya, teslimat için anlaşmalı kargo firmasına ve yasal yükümlülük halinde yetkili kurumlara aktarılır. Üçüncü kişilere pazarlama amaçlı satılmaz veya kiralanmaz.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Saklama ve Güvenlik</h2>
                <p>Kişisel verileriniz, ilgili mevzuatta öngörülen süreler boyunca (ör. e-ticaret kayıtları için 10 yıl) saklanır ve süre sonunda silinir veya anonim hale getirilir. Verileriniz SSL şifreleme ve erişim kontrolleriyle korunur.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">KVKK Kapsamındaki Haklarınız</h2>
                <p>KVKK'nın 11. maddesi uyarınca; verilerinizin işlenip işlenmediğini öğrenme, düzeltilmesini veya silinmesini talep etme, işlemeye itiraz etme ve zarara uğramanız halinde tazminat talep etme haklarına sahipsiniz. Taleplerinizi {{ \App\Models\Setting::get('email') }} adresine iletebilirsiniz; başvurunuz en geç 30 gün içinde yanıtlanır.</p>
            </div>

            <div>
                <h2 class="font-semibold text-secondary mb-2">Çerezler</h2>
                <p>Sitemiz; oturumunuzu ve sepetinizi hatırlamak için zorunlu çerezler kullanır. Tarayıcınızın ayarlarından çerezleri dilediğiniz zaman silebilir veya engelleyebilirsiniz; ancak bu durumda sepet ve üyelik özellikleri çalışmayabilir.</p>
            </div>
        </div>
    </div>
@endsection
