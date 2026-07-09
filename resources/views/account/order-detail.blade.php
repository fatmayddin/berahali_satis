@extends('layouts.app')

@section('title', 'Sipariş '.$order->order_number.' - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-4xl mx-auto px-5 pt-36">
        <a href="{{ route('account.orders') }}" class="text-tagline-2 text-secondary/50 hover:text-primary-600 transition">← Siparişlerim</a>
        <div class="flex flex-wrap items-center justify-between gap-3 mt-3 mb-8">
            <h1 class="text-heading-4 font-medium">{{ $order->order_number }}</h1>
            <span class="badge !py-1.5 !px-4 !text-xs font-medium
                @switch($order->status)
                    @case('paid') bg-nsgreenlight @break
                    @case('pending') bg-nsyellowlight @break
                    @case('failed') @case('cancelled') bg-nsred/30 @break
                    @default bg-nscyanlight
                @endswitch">
                {{ $order->status_label }}
            </span>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-[24px] border border-stroke2 shadow-1 p-7">
                <h2 class="text-heading-6 font-medium mb-4">Ürünler</h2>
                <div class="divide-y divide-stroke4">
                    @foreach($order->items as $item)
                        <div class="py-3.5 flex justify-between items-center gap-3">
                            <div>
                                <p class="font-medium">{{ $item->product_name }}</p>
                                <p class="text-tagline-2 text-secondary/40">{{ $item->product_code }} · {{ $item->quantity }} adet</p>
                            </div>
                            <span class="font-semibold whitespace-nowrap">{{ number_format($item->line_total, 2, ',', '.') }} ₺</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-stroke4 mt-2 pt-4 space-y-2.5 text-[15px]">
                    <div class="flex justify-between">
                        <span class="text-secondary/50">Ara Toplam</span>
                        <span>{{ number_format($order->subtotal, 2, ',', '.') }} ₺</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-secondary/50">Kargo</span>
                        <span>{{ $order->shipping_cost > 0 ? number_format($order->shipping_cost, 2, ',', '.').' ₺' : 'Ücretsiz' }}</span>
                    </div>
                    <div class="flex justify-between font-medium text-base">
                        <span>Toplam</span>
                        <span class="font-semibold text-primary-600">{{ number_format($order->total, 2, ',', '.') }} ₺</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-7 h-fit text-[15px] space-y-3.5">
                <h2 class="text-heading-6 font-medium">Teslimat Bilgileri</h2>
                <p><span class="text-secondary/50 text-tagline-2 block">Ad Soyad</span>{{ $order->name }}</p>
                <p><span class="text-secondary/50 text-tagline-2 block">Telefon</span>{{ $order->phone }}</p>
                <p><span class="text-secondary/50 text-tagline-2 block">Adres</span>{{ $order->address }} {{ $order->district }} / {{ $order->city }}</p>
                <p><span class="text-secondary/50 text-tagline-2 block">Teslimat Yöntemi</span>{{ $order->shipping_method_label }}</p>
                @if($order->note)
                    <p><span class="text-secondary/50 text-tagline-2 block">Not</span>{{ $order->note }}</p>
                @endif
                <p><span class="text-secondary/50 text-tagline-2 block">Sipariş Tarihi</span>{{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
        </div>
    </div>
@endsection
