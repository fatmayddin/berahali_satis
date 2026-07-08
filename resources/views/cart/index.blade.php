@extends('layouts.app')

@section('title', 'Sepetim - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-6xl mx-auto px-5 pt-36">
        <div class="text-center mb-10">
            <span class="badge badge-yellow mb-4">alışveriş</span>
            <h1 class="text-heading-3 font-medium">Sepetim</h1>
        </div>

        @if(empty($items))
            <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-14 text-center max-w-xl mx-auto">
                <p class="text-secondary/50 text-tagline-1">Sepetiniz boş.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mt-6"><span>Alışverişe Başla</span></a>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Ürünler --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($items as $item)
                        <div class="bg-white rounded-[20px] border border-stroke2 shadow-1 p-4 flex gap-4">
                            <a href="{{ route('products.show', $item['product']) }}" class="w-24 h-28 bg-bg4 rounded-xl overflow-hidden flex-shrink-0">
                                @if($item['product']->cover_image)
                                    <img src="{{ asset('storage/'.$item['product']->cover_image) }}" class="w-full h-full object-cover" alt="{{ $item['product']->name }}">
                                @endif
                            </a>
                            <div class="flex-1 flex flex-col">
                                <div class="flex justify-between gap-2">
                                    <div>
                                        <a href="{{ route('products.show', $item['product']) }}" class="font-medium hover:text-primary-600 transition">{{ $item['product']->name }}</a>
                                        <p class="text-tagline-2 text-secondary/50">{{ $item['product']->code }}
                                            @if($item['product']->is_cut && $item['length_cm'])
                                                · {{ rtrim(rtrim(number_format($item['product']->cut_width_cm, 1, ',', '.'), '0'), ',') }}×{{ rtrim(rtrim(number_format($item['length_cm'], 1, ',', '.'), '0'), ',') }} cm
                                                ({{ number_format($item['m2'], 2, ',', '.') }} m²)
                                            @elseif($item['product']->size_text)
                                                · {{ $item['product']->size_text }}
                                            @endif
                                        </p>
                                        @if($item['product']->is_cut)
                                            <span class="badge {{ $item['overlock'] ? 'badge-green' : 'badge-cyan' }} !py-0.5 !px-2.5 !text-xs mt-1">
                                                {{ $item['overlock'] ? 'overloklu' : 'overloksuz' }}
                                            </span>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('cart.remove', $item['key']) }}">
                                        @csrf @method('DELETE')
                                        <button class="text-secondary/40 hover:text-red-500 p-1 transition" title="Kaldır">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="mt-auto flex items-center justify-between">
                                    <form method="POST" action="{{ route('cart.update', $item['key']) }}">
                                        @csrf @method('PATCH')
                                        <select name="quantity" onchange="this.form.submit()" class="bg-white border border-stroke2 rounded-full px-4 py-1.5 text-sm outline-none cursor-pointer">
                                            @for($i = 1; $i <= min(10, max(1, $item['product']->stock)); $i++)
                                                <option value="{{ $i }}" {{ $item['quantity'] === $i ? 'selected' : '' }}>{{ $i }} adet</option>
                                            @endfor
                                        </select>
                                    </form>
                                    <span class="font-semibold text-lg">{{ number_format($item['line_total'], 2, ',', '.') }} ₺</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Özet --}}
                <div class="bg-white rounded-[20px] border border-stroke2 shadow-1 p-6 h-fit">
                    <h2 class="text-heading-6 font-medium mb-5">Sipariş Özeti</h2>
                    <div class="space-y-3 text-[15px]">
                        <div class="flex justify-between">
                            <span class="text-secondary/50">Ara Toplam</span>
                            <span class="font-medium">{{ number_format($subtotal, 2, ',', '.') }} ₺</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-secondary/50">Kargo</span>
                            <span class="font-medium">{{ $shipping > 0 ? number_format($shipping, 2, ',', '.').' ₺' : 'Ücretsiz' }}</span>
                        </div>
                        @php $freeLimit = (float) \App\Models\Setting::get('free_shipping_limit', 0); @endphp
                        @if($shipping > 0 && $freeLimit > 0)
                            <p class="text-tagline-2 bg-nsyellowlight rounded-xl px-4 py-2.5">
                                {{ number_format($freeLimit - $subtotal, 2, ',', '.') }} ₺ daha ekleyin, kargo ücretsiz olsun!
                            </p>
                        @endif
                        <div class="border-t border-stroke4 pt-3 flex justify-between text-base">
                            <span class="font-medium">Toplam</span>
                            <span class="font-semibold text-primary-600">{{ number_format($total, 2, ',', '.') }} ₺</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-full mt-6"><span>Ödemeye Geç</span></a>
                    <a href="{{ route('products.index') }}" class="block mt-2.5 text-center text-tagline-2 text-secondary/50 hover:text-secondary py-2 transition">
                        Alışverişe devam et
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
