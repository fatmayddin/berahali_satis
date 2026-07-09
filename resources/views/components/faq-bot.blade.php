{{-- SSS Botu: tıklamalı soru-cevap, ayarlardan dinamik değerler --}}
@php
    $botShipping = number_format((float) \App\Models\Setting::get('shipping_cost', 0), 0, ',', '.');
    $botFreeLimit = (float) \App\Models\Setting::get('free_shipping_limit', 0);
    $botSameDay = number_format((float) \App\Models\Setting::get('same_day_price', 0), 0, ',', '.');
    $botOverlock = number_format((float) \App\Models\Setting::get('overlock_price', 0), 0, ',', '.');
    $botWhatsapp = \App\Models\Setting::get('whatsapp');
    $botPhone = \App\Models\Setting::get('phone');

    $botFaqs = [
        [
            'q' => '🚚 Kargo ücreti ne kadar?',
            'a' => 'Standart kargo ücretimiz '.$botShipping.' ₺.'.($botFreeLimit > 0 ? ' '.number_format($botFreeLimit, 0, ',', '.').' ₺ ve üzeri siparişlerde kargo ücretsiz!' : ''),
        ],
        [
            'q' => '⚡ Aynı gün teslimat var mı?',
            'a' => 'Evet! Yakın bölgeler için siparişinizi mağaza aracımızla aynı gün elden teslim edebiliyoruz ('.$botSameDay.' ₺). Ödeme adımında "Aynı Gün Teslimat" seçeneğini işaretlemeniz yeterli.',
        ],
        [
            'q' => '📏 Kesme halı nasıl çalışıyor?',
            'a' => 'Eni sabit yolluklarımızda boyu siz seçersiniz; fiyat, metrekare üzerinden anında hesaplanır. Dilerseniz kenarlarına overlok da yaparız (+'.$botOverlock.' ₺). Kesme Halı Grubu kategorisine göz atın.',
        ],
        [
            'q' => '💳 Ödeme güvenli mi? Taksit var mı?',
            'a' => 'Tüm ödemeler iyzico güvenli ödeme altyapısıyla alınır ve kredi kartına taksit seçenekleri sunulur. Kart bilgileriniz sitemizde asla saklanmaz.',
        ],
        [
            'q' => '🔄 İade / değişim yapabiliyor muyum?',
            'a' => 'Ürün kaynaklı sorunlarda değişim ve iade yapıyoruz. Kesme (ölçüye özel kesilen) ürünlerde iade, ürün hatası dışında mümkün olmayabilir. Detay için bize ulaşın, hemen çözelim.',
        ],
        [
            'q' => '📍 Mağazanız nerede?',
            'a' => \App\Models\Setting::get('address', 'Adres bilgisi için bize ulaşın.').($botPhone ? ' · Tel: '.$botPhone : ''),
        ],
    ];
@endphp

<div x-data="{ botOpen: false, activeFaq: null }" class="fixed bottom-5 left-5 z-50">

    {{-- Panel --}}
    <div x-show="botOpen" x-cloak @click.outside="botOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute bottom-16 left-0 w-[320px] sm:w-[360px] bg-white rounded-[20px] border border-stroke2 shadow-card overflow-hidden">

        <div class="bg-secondary text-white px-5 py-4">
            <p class="font-semibold">Merhaba! 👋</p>
            <p class="text-tagline-2 text-white/60">Size nasıl yardımcı olabiliriz? Bir soru seçin:</p>
        </div>

        <div class="max-h-[380px] overflow-y-auto p-4 space-y-2">
            @foreach($botFaqs as $i => $faq)
                <div>
                    <button @click="activeFaq = activeFaq === {{ $i }} ? null : {{ $i }}"
                            class="w-full text-left text-[14px] font-medium px-4 py-2.5 rounded-2xl border transition"
                            :class="activeFaq === {{ $i }} ? 'bg-primary-50 border-primary-300 text-primary-700' : 'bg-bg3 border-transparent hover:border-stroke2'">
                        {{ $faq['q'] }}
                    </button>
                    <div x-show="activeFaq === {{ $i }}" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-2 ml-3 bg-white border border-stroke2 rounded-2xl rounded-tl-sm px-4 py-3 text-[14px] text-secondary/70 leading-relaxed shadow-1">
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border-t border-stroke4 p-4">
            @if($botWhatsapp)
                <a href="https://wa.me/{{ $botWhatsapp }}?text={{ urlencode('Merhaba 👋 bir sorum var.') }}" target="_blank" rel="noopener"
                   class="flex items-center justify-center gap-2 w-full bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2.5 rounded-full transition">
                    Cevabı bulamadım, WhatsApp'tan sorayım 💬
                </a>
            @else
                <a href="{{ route('contact') }}" class="flex items-center justify-center gap-2 w-full bg-secondary text-white text-sm font-medium py-2.5 rounded-full">
                    Cevabı bulamadım, iletişime geçeyim →
                </a>
            @endif
        </div>
    </div>

    {{-- Açma butonu --}}
    <button @click="botOpen = !botOpen"
            class="flex items-center gap-2 bg-secondary hover:bg-black text-white rounded-full py-3 px-4 shadow-card font-medium transition hover:scale-105">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"/>
        </svg>
        <span class="hidden sm:inline text-sm" x-text="botOpen ? 'Kapat' : 'Sorunuz mu var? 🤔'"></span>
    </button>
</div>
