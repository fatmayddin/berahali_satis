@extends('layouts.app')

@section('title', 'Ödeme - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-6xl mx-auto px-5 pt-36">
        <div class="text-center mb-10">
            <span class="badge badge-green mb-4">güvenli ödeme</span>
            <h1 class="text-heading-3 font-medium">Teslimat ve Ödeme</h1>
        </div>

        @if($errors->any())
            <div class="bg-nsred/20 border border-nsred rounded-2xl px-5 py-4 mb-6 text-sm max-w-3xl mx-auto">
                <ul class="list-disc ml-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.pay') }}" class="grid lg:grid-cols-3 gap-8">
            @csrf

            {{-- Teslimat bilgileri --}}
            <div class="lg:col-span-2 bg-white rounded-[24px] border border-stroke2 shadow-1 p-7 space-y-4">
                <h2 class="text-heading-6 font-medium">Teslimat Bilgileri</h2>

                @guest
                    <p class="text-tagline-2 bg-bg3 rounded-xl px-4 py-3">
                        Zaten üye misiniz?
                        <a href="{{ route('login') }}" class="text-primary-600 font-medium hover:underline">Giriş yapın</a>
                        veya üye olmadan devam edin.
                    </p>
                @endguest

                <div class="grid sm:grid-cols-2 gap-4">
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Ad Soyad *" required class="input">
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="E-posta *" required class="input">
                </div>
                <div class="grid sm:grid-cols-3 gap-4">
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="Telefon *" required class="input">
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="İl *" required class="input">
                    <input type="text" name="district" value="{{ old('district') }}" placeholder="İlçe" class="input">
                </div>
                <textarea name="address" rows="3" placeholder="Açık Adres *" required class="input !rounded-2xl">{{ old('address') }}</textarea>
                <textarea name="note" rows="2" placeholder="Sipariş notu (isteğe bağlı)" class="input !rounded-2xl">{{ old('note') }}</textarea>
            </div>

            {{-- Özet --}}
            <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-7 h-fit">
                <h2 class="text-heading-6 font-medium mb-5">Sipariş Özeti</h2>
                <div class="space-y-3 text-tagline-2 max-h-64 overflow-y-auto mb-4">
                    @foreach($items as $item)
                        <div class="flex justify-between gap-2">
                            <span class="text-secondary/60">{{ $item['product']->name }}@if($item['product']->is_cut && $item['length_cm']) ({{ rtrim(rtrim(number_format($item['length_cm'], 1, ',', '.'), '0'), ',') }} cm{{ $item['overlock'] ? ', overloklu' : '' }})@endif <span class="text-secondary/40">× {{ $item['quantity'] }}</span></span>
                            <span class="font-medium whitespace-nowrap">{{ number_format($item['line_total'], 2, ',', '.') }} ₺</span>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-3 text-[15px] border-t border-stroke4 pt-4">
                    <div class="flex justify-between">
                        <span class="text-secondary/50">Ara Toplam</span>
                        <span class="font-medium">{{ number_format($subtotal, 2, ',', '.') }} ₺</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-secondary/50">Kargo</span>
                        <span class="font-medium">{{ $shipping > 0 ? number_format($shipping, 2, ',', '.').' ₺' : 'Ücretsiz' }}</span>
                    </div>
                    <div class="border-t border-stroke4 pt-3 flex justify-between text-base">
                        <span class="font-medium">Toplam</span>
                        <span class="font-semibold text-primary-600">{{ number_format($total, 2, ',', '.') }} ₺</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-full mt-6"><span>Güvenli Ödemeye Geç</span></button>
                <p class="text-tagline-2 text-secondary/40 text-center mt-3.5">
                    Ödemeniz iyzico güvenli ödeme altyapısı ile alınır. Kart bilgileriniz sitemizde saklanmaz.
                </p>
            </div>
        </form>
    </div>
@endsection
