@extends('layouts.app')

@section('title', \App\Models\Setting::get('site_title', 'Bera Halı').' - '.\App\Models\Setting::get('home_headline'))

@section('content')

    {{-- Hero --}}
    <section class="relative pt-40 pb-16 lg:pt-48 lg:pb-24 overflow-hidden">
        {{-- Gradient blob'lar --}}
        <div class="pointer-events-none absolute top-[10%] -left-[6%] w-[280px] h-[280px] rounded-full opacity-30 blur-3xl"
             style="background: linear-gradient(135deg, #a585ff 0%, #ffc2ad 100%);"></div>
        <div class="pointer-events-none absolute top-[6%] -right-[4%] w-[320px] h-[320px] rounded-full opacity-30 blur-3xl"
             style="background: linear-gradient(135deg, #83e7ee 0%, #f9eb57 100%);"></div>

        <div class="max-w-6xl mx-auto px-5 text-center relative">
            <span class="badge badge-yellow mb-5">{{ \App\Models\Setting::get('site_title', 'Bera Halı') }} online mağaza</span>
            <h1 class="text-heading-3 md:text-heading-2 xl:text-heading-1 font-medium mb-4">
                {{ \App\Models\Setting::get('home_headline') }}
            </h1>
            <p class="text-secondary/60 text-tagline-1 max-w-2xl mx-auto mb-10 md:mb-12">
                {{ \App\Models\Setting::get('home_subline') }}
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-14">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg md:btn-xl"><span>Ürünleri İncele</span></a>
                <a href="{{ route('contact') }}" class="btn btn-white btn-lg md:btn-xl"><span>Bize Ulaşın</span></a>
            </div>

            {{-- Slider görselleri hero altında showcase --}}
            @if($sliders->isNotEmpty())
                <div x-data="{ current: 0, count: {{ $sliders->count() }} }"
                     x-init="count > 1 && setInterval(() => current = (current + 1) % count, 5000)"
                     class="relative rounded-[24px] overflow-hidden border border-stroke2 shadow-card bg-white p-2">
                    <div class="relative rounded-[18px] overflow-hidden">
                        <div class="flex transition-transform duration-700 ease-in-out" :style="`transform: translateX(-${current * 100}%)`">
                            @foreach($sliders as $slider)
                                <div class="w-full flex-shrink-0 relative">
                                    <img src="{{ asset('storage/'.$slider->image) }}" alt="{{ $slider->title }}" class="w-full h-[280px] md:h-[440px] object-cover">
                                    @if($slider->title || $slider->subtitle)
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent flex items-end">
                                            <div class="p-6 md:p-10 text-left text-white w-full">
                                                <h2 class="text-heading-5 md:text-heading-3 font-medium">{{ $slider->title }}</h2>
                                                @if($slider->subtitle)
                                                    <p class="mt-2 text-white/85 max-w-lg">{{ $slider->subtitle }}</p>
                                                @endif
                                                @if($slider->link && $slider->button_text)
                                                    <a href="{{ $slider->link }}" class="btn btn-white btn-md mt-5"><span>{{ $slider->button_text }}</span></a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if($sliders->count() > 1)
                        <div class="absolute bottom-6 right-8 flex gap-2 z-10">
                            @foreach($sliders as $i => $s)
                                <button @click="current = {{ $i }}" :class="current === {{ $i }} ? 'bg-white' : 'bg-white/40'" class="w-2.5 h-2.5 rounded-full transition"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
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

    {{-- Öne çıkan ürünler --}}
    @if($featured->isNotEmpty())
        <section class="max-w-6xl mx-auto px-5 py-12">
            <div class="text-center mb-10">
                <span class="badge badge-green mb-4">öne çıkanlar</span>
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
        </section>
    @endif

    {{-- Yeni gelenler --}}
    @if($latest->isNotEmpty())
        <section class="max-w-6xl mx-auto px-5 py-12">
            <div class="text-center mb-10">
                <span class="badge badge-cyan mb-4">yeni sezon</span>
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
            <div class="pointer-events-none absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[300px] rounded-full opacity-30 blur-3xl"
                 style="background: linear-gradient(135deg, #864ffe 0%, #23eed6 100%);"></div>
            <div class="relative">
                <span class="badge badge-yellow mb-5">güvenli alışveriş</span>
                <h2 class="text-heading-4 md:text-heading-3 font-medium text-white mb-4">Aradığınız halıyı bulamadınız mı?</h2>
                <p class="text-accent/60 max-w-xl mx-auto mb-9">
                    Bize ulaşın, mağazamızdaki tüm seçenekleri birlikte değerlendirelim. Ödemeleriniz iyzico güvencesiyle alınır.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg"><span>İletişime Geç</span></a>
                    @if(\App\Models\Setting::get('whatsapp'))
                        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp') }}" target="_blank" class="btn btn-white btn-lg"><span>WhatsApp'tan Yaz</span></a>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
