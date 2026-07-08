@extends('layouts.app')

@section('title', 'Ödeme - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-2xl mx-auto px-5 pt-36">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-7">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
                <h1 class="text-heading-5 font-medium">Kart Bilgileri</h1>
                <span class="badge badge-primary !text-xs">Sipariş No: {{ $order->order_number }}</span>
            </div>

            <div class="bg-bg3 rounded-2xl px-5 py-3.5 mb-6 flex justify-between">
                <span class="font-medium">Ödenecek Tutar</span>
                <span class="font-semibold text-primary-600">{{ number_format($order->total, 2, ',', '.') }} ₺</span>
            </div>

            {{-- İyzico ödeme formu --}}
            <div id="iyzipay-checkout-form" class="responsive"></div>
            {!! $checkoutFormContent !!}

            <p class="text-tagline-2 text-secondary/40 text-center mt-6">
                Ödemeniz iyzico güvenli ödeme altyapısı üzerinden gerçekleştirilir.
            </p>
        </div>
    </div>
@endsection
