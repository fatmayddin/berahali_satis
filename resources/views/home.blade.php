@extends('layouts.app')

@section('title', \App\Models\Setting::get('site_title', 'Bera Halı').' - '.\App\Models\Setting::get('home_headline'))

@section('content')

    {{-- Hero: solda yazı, sağda slider --}}
    <section class="relative pt-32 pb-12 lg:pt-36 lg:pb-16 overflow-hidden">
        <div class="max-w-6xl mx-auto px-5 relative">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-12 items-center">

                {{-- Yazı --}}
                <div class="text-center lg:text-left order-2 lg:order-1">
                    <span class="badge badge-yellow mb-5">{{ \App\Models\Setting::get('site_title', 'Bera Halı') }} Online Mağaza</span>
                    <h1 class="text-heading-3 md:text-heading-2 font-medium mb-4">
                        {{ \App\Models\Setting::get('home_headline') }}
                    </h1>
                    <p class="text-secondary/60 text-tagline-1 mb-8 lg:max-w-md">
                        {{ \App\Models\Setting::get('home_subline') }}
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg"><span>Ürünleri İncele</span></a>
                        <a href="{{ route('contact') }}" class="btn btn-white btn-lg"><span>Bize Ulaşın</span></a>
                    </div>

                    {{-- Mini güven satırı --}}
                    <div class="flex items-center justify-center lg:justify-start gap-5 mt-9 text-tagline-2 text-secondary/50">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                            iyzico güvenli ödeme
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                            5.000+ ürün seçeneği
                        </span>
                    </div>
                </div>

                {{-- Slider --}}
                <div class="order-1 lg:order-2 relative">
                    <img src="{{ asset('images/glow-warm.png') }}" alt="" aria-hidden="true"
                         class="pointer-events-none select-none absolute -top-20 -right-20 w-[340px] opacity-60 -z-10">

                    @if($sliders->isNotEmpty())
                        <div x-data="{ current: 0, count: {{ $sliders->count() }} }"
                             x-init="count > 1 && setInterval(() => current = (current + 1) % count, 5000)"
                             class="relative rounded-[24px] overflow-hidden border border-stroke2 shadow-card bg-white p-2">
                            <div class="relative rounded-[18px] overflow-hidden">
                                <div class="flex transition-transform duration-700 ease-in-out" :style="`transform: translateX(-${current * 100}%)`">
                                    @foreach($sliders as $slider)
                                        <div class="w-full flex-shrink-0 relative">
                                            <img src="{{ asset('storage/'.$slider->image) }}" alt="{{ $slider->title }}" class="w-full h-[280px] md:h-[420px] object-cover">
                                            @if($slider->title || $slider->subtitle)
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent flex items-end">
                                                    <div class="p-6 md:p-8 text-left text-white w-full">
                                                        <h2 class="text-heading-6 md:text-heading-5 font-medium">{{ $slider->title }}</h2>
                                                        @if($slider->subtitle)
                                                            <p class="mt-1.5 text-white/85 text-tagline-2 max-w-md">{{ $slider->subtitle }}</p>
                                                        @endif
                                                        @if($slider->link && $slider->button_text)
                                                            <a href="{{ $slider->link }}" class="btn btn-white btn-md mt-4"><span>{{ $slider->button_text }}</span></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if($sliders->count() > 1)
                                <div class="absolute bottom-5 right-6 flex gap-2 z-10">
                                    @foreach($sliders as $i => $s)
                                        <button @click="current = {{ $i }}" :class="current === {{ $i }} ? 'bg-white' : 'bg-white/40'" class="w-2.5 h-2.5 rounded-full transition"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Slider yoksa öne çıkan ürün kolajı --}}
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($featured->take(2) as $i => $fp)
                                <div class="{{ $i === 1 ? 'mt-8' : '' }}">
                                    @include('components.product-card', ['product' => $fp])
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Özellikler --}}
    <section class="max-w-6xl mx-auto px-5 pb-8">
        <div class="grid sm:grid-cols-3 gap-5">
            @php $badges = ['badge-yellow', 'badge-green', 'badge-cyan']; @endphp
            @foreach([1, 2, 3] as $i)
                <div class="bg-white rounded-[20px] border border-stroke2 shadow-1 p-7">
                    <span class="w-12 h-12 rounded-full flex items-center justify-center mb-4
                        {{ $i === 1 ? 'bg-nsyellowlight' : ($i === 2 ? 'bg-nsgreenlight' : 'bg-nscyanlight') }}">
                        @if($i === 1)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                        @elseif($i === 2)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                        @endif
                    </span>
                    <h3 class="text-heading-6 font-medium mb-1.5">{{ \App\Models\Setting::get("home_feature_{$i}_title") }}</h3>
                    <p class="text-secondary/60 text-tagline-2 leading-relaxed">{{ \App\Models\Setting::get("home_feature_{$i}_text") }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Kategoriler --}}
    @if($categories->isNotEmpty())
        <section class="max-w-6xl mx-auto px-5 py-8 text-center">
            <div class="flex flex-wrap gap-3 justify-center">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['kategori' => $category->slug]) }}"
                       class="bg-white border border-stroke2 hover:border-primary-400 hover:text-primary-600 px-5 py-2 rounded-full text-[15px] text-secondary/70 shadow-1 transition-all">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Öne çıkan ürünler (beyaz bant) --}}
    @if($featured->isNotEmpty())
        <section class="relative bg-white/80 border-y border-stroke2/80 mt-10 py-14 overflow-hidden">
            <img src="{{ asset('images/glow-warm.png') }}" alt="" aria-hidden="true"
                 class="pointer-events-none select-none absolute -bottom-40 -left-44 w-[440px] opacity-30">
            <div class="max-w-6xl mx-auto px-5 relative">
                <div class="text-center mb-10">
                    <h2 class="text-heading-4 md:text-heading-3 font-medium">Öne Çıkan Ürünler</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @foreach($featured as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('products.index') }}" class="btn btn-white btn-lg"><span>Tüm Ürünler</span></a>
                </div>
            </div>
        </section>
    @endif

    {{-- Kesme halı promosu --}}
    <section class="max-w-6xl mx-auto px-5 pt-14">
        <div class="relative overflow-hidden bg-white rounded-[32px] border border-stroke2 shadow-1 grid md:grid-cols-2">
            <img src="{{ asset('images/glow-soft.png') }}" alt="" aria-hidden="true"
                 class="pointer-events-none select-none absolute -top-32 -right-32 w-[420px] opacity-30">

            {{-- İçerik --}}
            <div class="p-8 md:p-12 relative">
                <h2 class="text-heading-4 md:text-heading-3 font-medium mb-4">İstediğiniz boyda,<br>milimi milimine</h2>
                <p class="text-secondary/60 mb-7 leading-relaxed">
                    Koridorunuz kaç metre olursa olsun; eni sabit yolluklarımızın boyunu siz seçin,
                    fiyatı anında hesaplansın. Dilerseniz kenarlarına overlok da yapalım.
                </p>
                <ul class="space-y-3 mb-9 text-[15px]">
                    @foreach(['Sabit en, dilediğiniz boy', 'Anında m² üzerinden fiyat hesabı', 'İsteğe bağlı overlok', 'Ölçünüze göre kesilir, kapınıza gelir'] as $li)
                        <li class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-nsgreenlight flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            </span>
                            {{ $li }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ $cutProduct ? route('products.show', $cutProduct) : route('products.index', ['kategori' => 'kesme-hali-grubu']) }}"
                   class="btn btn-primary btn-lg"><span>Kesme Halılara Bak</span></a>
            </div>

            {{-- Stilize yolluk görseli --}}
            <div class="relative bg-bg4/70 flex items-center justify-center p-10 md:p-12 min-h-[380px]">
                <div class="relative">
                    {{-- Saçak (üst) --}}
                    <div class="h-3 w-48 md:w-56 mx-auto"
                         style="background-image: repeating-linear-gradient(90deg, #c9cfd8 0 2px, transparent 2px 7px);"></div>
                    {{-- Halı gövdesi --}}
                    <div class="w-48 md:w-56 h-72 md:h-80 shadow-card relative overflow-hidden"
                         style="background:
                                repeating-linear-gradient(180deg,
                                    #6d1fe2 0 18px, #864ffe 18px 24px, #f4f2fe 24px 28px,
                                    #864ffe 28px 34px, #6d1fe2 34px 52px, #fdf7bc 52px 58px,
                                    #6d1fe2 58px 64px, #cdf5f8 64px 70px);">
                        <div class="absolute inset-2 border-2 border-white/25"></div>
                    </div>
                    {{-- Saçak (alt) --}}
                    <div class="h-3 w-48 md:w-56 mx-auto"
                         style="background-image: repeating-linear-gradient(90deg, #c9cfd8 0 2px, transparent 2px 7px);"></div>

                    {{-- En etiketi --}}
                    <span class="absolute -top-9 left-1/2 -translate-x-1/2 badge bg-white shadow-1 !text-xs font-medium whitespace-nowrap">← 80 cm →</span>
                    {{-- Boy etiketi --}}
                    <span class="absolute top-1/2 -right-20 md:-right-24 -translate-y-1/2 rotate-90 badge bg-white shadow-1 !text-xs font-medium whitespace-nowrap">← boyu siz seçin →</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Yeni gelenler --}}
    @if($latest->isNotEmpty())
        <section class="max-w-6xl mx-auto px-5 py-12">
            <div class="text-center mb-10">
                <span class="badge badge-cyan mb-4">Yeni Sezon</span>
                <h2 class="text-heading-4 md:text-heading-3 font-medium">Yeni Gelenler</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($latest as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="max-w-6xl mx-auto px-5 pt-12">
        <div class="relative overflow-hidden bg-secondary rounded-[32px] px-6 py-16 md:py-20 text-center">
            <img src="{{ asset('images/glow-orb.png') }}" alt="" aria-hidden="true"
                 class="pointer-events-none select-none absolute -top-64 left-1/2 -translate-x-1/2 w-[680px] opacity-40">
            <img src="{{ asset('images/glow-cyan.png') }}" alt="" aria-hidden="true"
                 class="pointer-events-none select-none absolute -bottom-48 -left-32 w-[380px] opacity-25">
            <div class="relative">
                <span class="badge badge-yellow mb-5">güvenli alışveriş</span>
                <h2 class="text-heading-4 md:text-heading-3 font-medium text-white mb-4">Aradığınız halıyı bulamadınız mı?</h2>
                <p class="text-accent/60 max-w-xl mx-auto mb-9">
                    Bize ulaşın, mağazamızdaki tüm seçenekleri birlikte değerlendirelim. Ödemeleriniz iyzico güvencesiyle alınır.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg"><span>İletişime Geç</span></a>
                    @if(\App\Models\Setting::get('whatsapp'))
                        <a href="https://wa.me/{{ \App\Models\Setting::whatsappNumber() }}" target="_blank" class="btn btn-white btn-lg"><span>WhatsApp'tan Yaz</span></a>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
