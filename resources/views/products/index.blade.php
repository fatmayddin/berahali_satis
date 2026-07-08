@extends('layouts.app')

@section('title', 'Ürünler - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    {{-- Sayfa başlığı --}}
    <section class="pt-40 pb-10 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute top-0 -right-[4%] w-[280px] h-[280px] rounded-full opacity-25 blur-3xl"
             style="background: linear-gradient(135deg, #a585ff 0%, #ffc2ad 100%);"></div>
        <div class="max-w-6xl mx-auto px-5 relative">
            <span class="badge badge-yellow mb-4">koleksiyon</span>
            <h1 class="text-heading-3 md:text-heading-2 font-medium">Ürünlerimiz</h1>
            <p class="text-secondary/50 mt-3">{{ $products->total() }} ürün listeleniyor</p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-5 grid lg:grid-cols-4 gap-8">

        {{-- Filtreler --}}
        <aside class="lg:col-span-1" x-data="{ filtersOpen: window.innerWidth >= 1024 }">
            <button class="lg:hidden w-full bg-white border border-stroke2 rounded-full px-5 py-3 font-medium mb-3 flex justify-between items-center shadow-1"
                    @click="filtersOpen = !filtersOpen">
                Filtrele <span x-text="filtersOpen ? '−' : '+'"></span>
            </button>

            <form method="GET" action="{{ route('products.index') }}" x-show="filtersOpen" x-cloak
                  class="bg-white rounded-[20px] border border-stroke2 shadow-1 p-6 space-y-7 lg:!block">

                @if(request('sirala'))
                    <input type="hidden" name="sirala" value="{{ request('sirala') }}">
                @endif

                {{-- Kategori --}}
                <div>
                    <h3 class="font-medium mb-3">Kategori</h3>
                    <div class="space-y-2.5 text-[15px] text-secondary/70">
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-secondary">
                            <input type="radio" name="kategori" value="" {{ !request('kategori') ? 'checked' : '' }} class="accent-primary-500">
                            Tümü
                        </label>
                        @foreach($categories as $category)
                            <label class="flex items-center gap-2.5 cursor-pointer hover:text-secondary">
                                <input type="radio" name="kategori" value="{{ $category->slug }}"
                                       {{ request('kategori') === $category->slug ? 'checked' : '' }} class="accent-primary-500">
                                {{ $category->name }} <span class="text-secondary/40">({{ $category->products_count }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- m2 aralığı --}}
                <div>
                    <h3 class="font-medium mb-3">Metrekare (m²)</h3>
                    <div class="flex gap-2">
                        <input type="number" step="0.1" min="0" name="m2_min" value="{{ request('m2_min') }}" placeholder="Min" class="input !py-2.5 !px-4 text-sm">
                        <input type="number" step="0.1" min="0" name="m2_max" value="{{ request('m2_max') }}" placeholder="Max" class="input !py-2.5 !px-4 text-sm">
                    </div>
                </div>

                {{-- Fiyat aralığı --}}
                <div>
                    <h3 class="font-medium mb-3">Fiyat (₺)</h3>
                    <div class="flex gap-2">
                        <input type="number" min="0" name="fiyat_min" value="{{ request('fiyat_min') }}" placeholder="Min" class="input !py-2.5 !px-4 text-sm">
                        <input type="number" min="0" name="fiyat_max" value="{{ request('fiyat_max') }}" placeholder="Max" class="input !py-2.5 !px-4 text-sm">
                    </div>
                </div>

                <label class="flex items-center gap-2.5 text-[15px] text-secondary/70 cursor-pointer">
                    <input type="checkbox" name="indirimli" value="1" {{ request('indirimli') ? 'checked' : '' }} class="accent-primary-500 rounded">
                    Sadece indirimli ürünler
                </label>

                <div class="flex flex-col gap-2">
                    <button type="submit" class="btn btn-primary btn-md w-full"><span>Uygula</span></button>
                    <a href="{{ route('products.index') }}" class="text-center text-sm text-secondary/50 hover:text-secondary py-1.5 transition">Filtreleri temizle</a>
                </div>
            </form>
        </aside>

        {{-- Ürünler --}}
        <div class="lg:col-span-3">
            {{-- Sıralama --}}
            <form method="GET" class="flex justify-end mb-5">
                @foreach(request()->except(['sirala', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <select name="sirala" onchange="this.form.submit()"
                        class="bg-white border border-stroke2 rounded-full px-5 py-2.5 text-sm shadow-1 outline-none cursor-pointer">
                    <option value="" {{ !request('sirala') ? 'selected' : '' }}>En Yeni</option>
                    <option value="fiyat-artan" {{ request('sirala') === 'fiyat-artan' ? 'selected' : '' }}>Fiyat: Düşükten Yükseğe</option>
                    <option value="fiyat-azalan" {{ request('sirala') === 'fiyat-azalan' ? 'selected' : '' }}>Fiyat: Yüksekten Düşüğe</option>
                    <option value="m2-artan" {{ request('sirala') === 'm2-artan' ? 'selected' : '' }}>m²: Küçükten Büyüğe</option>
                    <option value="m2-azalan" {{ request('sirala') === 'm2-azalan' ? 'selected' : '' }}>m²: Büyükten Küçüğe</option>
                </select>
            </form>

            @if($products->isEmpty())
                <div class="bg-white rounded-[20px] border border-stroke2 shadow-1 p-14 text-center text-secondary/50">
                    Aradığınız kriterlere uygun ürün bulunamadı.
                    <a href="{{ route('products.index') }}" class="text-primary-600 font-medium block mt-2">Filtreleri temizle</a>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
