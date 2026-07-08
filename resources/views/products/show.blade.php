@extends('layouts.app')

@section('title', $product->name.' - '.\App\Models\Setting::get('site_title', 'Bera Halı'))
@section('meta_description', Str::limit(strip_tags($product->description ?? $product->name), 155))

@section('content')
    <div class="max-w-6xl mx-auto px-5 pt-32 lg:pt-36">

        {{-- Breadcrumb --}}
        <nav class="text-tagline-2 text-secondary/50 mb-8">
            <a href="{{ route('home') }}" class="hover:text-primary-600">Anasayfa</a>
            <span class="mx-1.5">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-primary-600">Ürünler</a>
            @if($product->category)
                <span class="mx-1.5">/</span>
                <a href="{{ route('products.index', ['kategori' => $product->category->slug]) }}" class="hover:text-primary-600">{{ $product->category->name }}</a>
            @endif
            <span class="mx-1.5">/</span>
            <span class="text-secondary">{{ $product->name }}</span>
        </nav>

        <div class="grid md:grid-cols-2 gap-10">
            {{-- Galeri --}}
            @php
                $gallery = collect([$product->cover_image])->filter()
                    ->merge($product->images->pluck('image'))
                    ->values();
            @endphp
            <div x-data="{ active: 0 }">
                <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-3">
                    <div class="aspect-[4/5] bg-bg4 rounded-2xl overflow-hidden">
                        @if($gallery->isEmpty())
                            <div class="w-full h-full flex items-center justify-center text-stroke3">
                                <svg class="w-24 h-24" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Z"/>
                                </svg>
                            </div>
                        @else
                            @foreach($gallery as $i => $img)
                                <img x-show="active === {{ $i }}" src="{{ asset('storage/'.$img) }}" alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            @endforeach
                        @endif
                    </div>
                </div>
                @if($gallery->count() > 1)
                    <div class="flex gap-2.5 mt-3 overflow-x-auto pb-1">
                        @foreach($gallery as $i => $img)
                            <button @click="active = {{ $i }}"
                                    :class="active === {{ $i }} ? 'border-primary-500 ring-2 ring-primary-200' : 'border-stroke2 opacity-70 hover:opacity-100'"
                                    class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 border bg-white p-1 transition">
                                <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover rounded-lg" alt="">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Bilgiler --}}
            <div>
                <span class="badge badge-primary !text-xs mb-3">{{ $product->code }}@if($product->category) · {{ $product->category->name }}@endif</span>
                <h1 class="text-heading-4 md:text-heading-3 font-medium">{{ $product->name }}</h1>

                <div class="mt-5 flex items-end gap-3 flex-wrap">
                    @if($product->is_cut)
                        <span class="text-heading-3 font-semibold text-primary-600">{{ number_format($product->price_per_m2, 2, ',', '.') }} ₺<span class="text-tagline-1 font-normal text-secondary/50">/m²</span></span>
                        <span class="badge badge-cyan !py-1 !px-3 !text-xs font-medium mb-2">istediğiniz boyda kesilir</span>
                    @elseif($product->has_discount)
                        <span class="text-heading-3 font-semibold text-primary-600">{{ number_format($product->discount_price, 2, ',', '.') }} ₺</span>
                        <span class="text-tagline-1 text-secondary/40 line-through mb-1.5">{{ number_format($product->total_price, 2, ',', '.') }} ₺</span>
                        <span class="badge badge-yellow !py-1 !px-3 !text-xs font-medium mb-2">%{{ $product->discount_percent }} indirim</span>
                    @else
                        <span class="text-heading-3 font-semibold">{{ number_format($product->total_price, 2, ',', '.') }} ₺</span>
                    @endif
                </div>

                {{-- Ölçü bilgileri --}}
                @if($product->is_cut)
                    <div class="mt-6 bg-white border border-stroke2 rounded-2xl p-5 grid grid-cols-3 gap-3 text-center">
                        <div>
                            <div class="text-tagline-2 text-secondary/50">En (sabit)</div>
                            <div class="font-medium mt-0.5">{{ rtrim(rtrim(number_format($product->cut_width_cm, 1, ',', '.'), '0'), ',') }} cm</div>
                        </div>
                        <div>
                            <div class="text-tagline-2 text-secondary/50">Boy</div>
                            <div class="font-medium mt-0.5">{{ $product->cut_min_cm }}–{{ $product->cut_max_cm }} cm arası</div>
                        </div>
                        <div>
                            <div class="text-tagline-2 text-secondary/50">m² Fiyatı</div>
                            <div class="font-medium mt-0.5">{{ number_format($product->price_per_m2, 2, ',', '.') }} ₺</div>
                        </div>
                    </div>
                @else
                <div class="mt-6 bg-white border border-stroke2 rounded-2xl p-5 grid grid-cols-3 gap-3 text-center">
                    @if($product->size_text)
                        <div>
                            <div class="text-tagline-2 text-secondary/50">Ölçü</div>
                            <div class="font-medium mt-0.5">{{ $product->size_text }}</div>
                        </div>
                    @endif
                    @if($product->m2)
                        <div>
                            <div class="text-tagline-2 text-secondary/50">Metrekare</div>
                            <div class="font-medium mt-0.5">{{ rtrim(rtrim(number_format($product->m2, 2, ',', '.'), '0'), ',') }} m²</div>
                        </div>
                    @endif
                    @if($product->price_per_m2)
                        <div>
                            <div class="text-tagline-2 text-secondary/50">m² Fiyatı</div>
                            <div class="font-medium mt-0.5">{{ number_format($product->price_per_m2, 2, ',', '.') }} ₺</div>
                        </div>
                    @endif
                </div>
                @endif

                {{-- Sepete ekle --}}
                <div class="mt-6">
                    @if($product->stock > 0)
                        @if($product->is_cut)
                            @php $overlockFee = (float) \App\Models\Setting::get('overlock_price', 0); @endphp
                            <form method="POST" action="{{ route('cart.add', $product) }}"
                                  x-data="{
                                      len: {{ $product->cut_min_cm }},
                                      overlock: false,
                                      width: {{ (float) $product->cut_width_cm }},
                                      ppm2: {{ (float) $product->price_per_m2 }},
                                      fee: {{ $overlockFee }},
                                      min: {{ $product->cut_min_cm }},
                                      max: {{ $product->cut_max_cm }},
                                      m2() { return (this.width / 100) * ((this.len || 0) / 100); },
                                      price() { let p = this.m2() * this.ppm2; if (this.overlock) p += this.fee; return p; },
                                      fmt(v) { return v.toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
                                  }"
                                  class="bg-white border border-stroke2 rounded-2xl p-5 space-y-4">
                                @csrf

                                <div>
                                    <label class="text-tagline-2 text-secondary/60 block mb-1.5">
                                        Boy (cm) — {{ $product->cut_min_cm }} ile {{ $product->cut_max_cm }} cm arası
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="length_cm" x-model.number="len"
                                               :min="min" :max="max" step="10" required
                                               class="input !w-36 text-center font-medium">
                                        <input type="range" x-model.number="len" :min="min" :max="max" step="10"
                                               class="flex-1 accent-primary-500">
                                    </div>
                                    @error('length_cm')
                                        <p class="text-tagline-2 text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <label class="flex items-center justify-between gap-3 bg-bg3 rounded-xl px-4 py-3 cursor-pointer">
                                    <span class="flex items-center gap-2.5">
                                        <input type="checkbox" name="overlock" value="1" x-model="overlock" class="accent-primary-500 rounded w-4 h-4">
                                        <span class="text-[15px]">Overlok yapılsın</span>
                                    </span>
                                    @if($overlockFee > 0)
                                        <span class="text-tagline-2 text-secondary/50">+{{ number_format($overlockFee, 2, ',', '.') }} ₺</span>
                                    @endif
                                </label>

                                <div class="flex items-center justify-between border-t border-stroke4 pt-4">
                                    <div>
                                        <p class="text-tagline-2 text-secondary/50">
                                            <span x-text="fmt(m2())"></span> m² ·
                                            {{ rtrim(rtrim(number_format($product->cut_width_cm, 1, ',', '.'), '0'), ',') }} cm en × <span x-text="len"></span> cm boy
                                        </p>
                                        <p class="text-heading-5 font-semibold text-primary-600 mt-0.5" x-text="fmt(price()) + ' ₺'"></p>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg"><span>Sepete Ekle</span></button>
                                </div>
                            </form>
                            <p class="text-tagline-2 text-secondary/50 mt-2.5">Kesim ölçünüze göre hazırlanır · iyzico ile güvenli ödeme</p>
                        @else
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="flex gap-3">
                                @csrf
                                @if($product->stock > 1)
                                    <select name="quantity" class="bg-white border border-stroke2 rounded-full px-5 py-3 shadow-1 outline-none cursor-pointer">
                                        @for($i = 1; $i <= min(10, $product->stock); $i++)
                                            <option value="{{ $i }}">{{ $i }} adet</option>
                                        @endfor
                                    </select>
                                @endif
                                <button type="submit" class="btn btn-primary btn-xl flex-1"><span>Sepete Ekle</span></button>
                            </form>
                            <p class="text-tagline-2 text-secondary/50 mt-2.5">Stok: {{ $product->stock }} adet · iyzico ile güvenli ödeme</p>
                        @endif
                    @else
                        <div class="bg-bg12 text-secondary/50 text-center font-medium py-4 rounded-full">Bu ürün tükendi</div>
                    @endif
                </div>

                {{-- Özellikler --}}
                @if(!empty($product->features))
                    <div class="mt-8">
                        <h2 class="text-heading-6 font-medium mb-3">Ürün Özellikleri</h2>
                        <div class="bg-white border border-stroke2 rounded-2xl px-5 divide-y divide-stroke4">
                            @foreach($product->features as $feature)
                                <div class="py-3 flex text-[15px]">
                                    <span class="text-secondary/50 w-1/3">{{ $feature['name'] ?? '' }}</span>
                                    <span class="font-medium">{{ $feature['value'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Açıklama --}}
                @if($product->description)
                    <div class="mt-8">
                        <h2 class="text-heading-6 font-medium mb-3">Açıklama</h2>
                        <div class="text-secondary/60 leading-relaxed text-[15px] space-y-2">
                            {!! $product->description !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Benzer ürünler --}}
        @if($related->isNotEmpty())
            <section class="mt-20">
                <div class="text-center mb-10">
                    <span class="badge badge-cyan mb-4">benzer ürünler</span>
                    <h2 class="text-heading-4 font-medium">Bunlar da İlginizi Çekebilir</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @foreach($related as $rp)
                        @include('components.product-card', ['product' => $rp])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
