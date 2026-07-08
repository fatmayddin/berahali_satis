@props(['product'])

<a href="{{ route('products.show', $product) }}"
   class="group bg-white rounded-[20px] border border-stroke2 shadow-1 hover:shadow-card hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col p-3">
    <div class="relative aspect-[4/5] bg-bg4 rounded-2xl overflow-hidden">
        @if($product->cover_image)
            <img src="{{ asset('storage/'.$product->cover_image) }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-stroke3">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Z"/>
                </svg>
            </div>
        @endif

        @if($product->has_discount)
            <span class="absolute top-3 left-3 badge badge-yellow !py-1 !px-3 !text-xs font-medium">%{{ $product->discount_percent }} indirim</span>
        @elseif($product->is_cut)
            <span class="absolute top-3 left-3 badge badge-cyan !py-1 !px-3 !text-xs font-medium">istediğiniz boyda</span>
        @endif

        @if($product->stock < 1)
            <span class="absolute top-3 right-3 badge !py-1 !px-3 !text-xs font-medium bg-secondary text-white">Tükendi</span>
        @elseif(!$product->is_cut && $product->stock <= 2)
            <span class="absolute top-3 right-3 badge !py-1 !px-3 !text-xs font-medium bg-nsred/80 text-secondary">Son {{ $product->stock }} adet</span>
        @endif
    </div>

    <div class="px-2 pt-4 pb-2 flex flex-col flex-1">
        <span class="text-tagline-2 text-secondary/50 mb-1">{{ $product->code }}@if($product->category) · {{ $product->category->name }}@endif</span>
        <h3 class="font-medium leading-snug group-hover:text-primary-600 transition">{{ $product->name }}</h3>
        @if($product->is_cut)
            <p class="text-tagline-2 text-secondary/50 mt-1">
                {{ rtrim(rtrim(number_format($product->cut_width_cm, 1, ',', '.'), '0'), ',') }} cm en · boyu siz seçin
            </p>
        @elseif($product->size_text || $product->m2)
            <p class="text-tagline-2 text-secondary/50 mt-1">
                {{ $product->size_text }}@if($product->m2) · {{ rtrim(rtrim(number_format($product->m2, 2, ',', '.'), '0'), ',') }} m²@endif
            </p>
        @endif
        <div class="mt-auto pt-3 flex items-baseline gap-2">
            @if($product->is_cut)
                <span class="text-lg font-semibold">{{ number_format($product->price_per_m2, 2, ',', '.') }} ₺<span class="text-tagline-2 font-normal text-secondary/50">/m²</span></span>
            @elseif($product->has_discount)
                <span class="text-lg font-semibold text-primary-600">{{ number_format($product->discount_price, 2, ',', '.') }} ₺</span>
                <span class="text-tagline-2 text-secondary/40 line-through">{{ number_format($product->total_price, 2, ',', '.') }} ₺</span>
            @else
                <span class="text-lg font-semibold">{{ number_format($product->total_price, 2, ',', '.') }} ₺</span>
            @endif
        </div>
    </div>
</a>
