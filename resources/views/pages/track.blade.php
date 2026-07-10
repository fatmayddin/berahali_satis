@extends('layouts.app')

@section('title', 'Sipariş Takip - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <section class="pt-40 pb-10 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute top-0 -left-[4%] w-[260px] h-[260px] rounded-full opacity-25 blur-3xl"
             style="background: linear-gradient(135deg, #83e7ee 0%, #f9eb57 100%);"></div>
        <div class="max-w-6xl mx-auto px-5 relative">
            <span class="badge badge-cyan mb-4">Siparişim nerede?</span>
            <h1 class="text-heading-3 md:text-heading-2 font-medium">Sipariş Takip</h1>
            <p class="text-secondary/60 text-tagline-1 max-w-xl mx-auto mt-4">
                Sipariş numaranız ve sipariş verirken kullandığınız e-posta adresi ile durumunuzu sorgulayın.
            </p>
        </div>
    </section>

    <div class="max-w-2xl mx-auto px-5">
        {{-- Sorgu formu --}}
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-7">
            @if($errors->any())
                <div class="bg-nsred/20 border border-nsred rounded-2xl px-4 py-3 mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('orders.track.lookup') }}" class="flex flex-col sm:flex-row gap-4">
                @csrf
                <input type="text" name="order_number" value="{{ old('order_number', isset($order) ? $order->order_number : '') }}"
                       placeholder="Sipariş No (örn: BH260709ABC12)" required class="input flex-1">
                <input type="email" name="email" value="{{ old('email', isset($order) ? $order->email : '') }}"
                       placeholder="E-posta" required class="input flex-1">
                <button type="submit" class="btn btn-primary btn-md sm:btn-lg"><span>Sorgula</span></button>
            </form>
        </div>

        {{-- Sonuç --}}
        @isset($order)
            <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-7 mt-6">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-7">
                    <div>
                        <p class="font-semibold text-lg">{{ $order->order_number }}</p>
                        <p class="text-tagline-2 text-secondary/50">{{ $order->created_at->format('d.m.Y H:i') }} · {{ $order->shipping_method_label }}</p>
                    </div>
                    <span class="font-semibold text-primary-600">{{ number_format($order->total, 2, ',', '.') }} ₺</span>
                </div>

                @include('components.order-timeline', ['order' => $order])

                <div class="border-t border-stroke4 mt-7 pt-4">
                    <p class="text-tagline-2 text-secondary/50 mb-2">Sipariş İçeriği</p>
                    <ul class="space-y-1.5 text-[15px]">
                        @foreach($order->items as $item)
                            <li class="flex justify-between gap-2">
                                <span>{{ $item->product_name }} <span class="text-secondary/40">× {{ $item->quantity }}</span></span>
                                <span class="font-medium whitespace-nowrap">{{ number_format($item->line_total, 2, ',', '.') }} ₺</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endisset
    </div>
@endsection
