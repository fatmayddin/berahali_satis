@extends('layouts.app')

@section('title', 'Siparişiniz Alındı - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-xl mx-auto px-5 pt-40 text-center">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-10">
            <div class="w-16 h-16 mx-auto bg-nsgreenlight rounded-full flex items-center justify-center mb-5">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
            </div>
            <h1 class="text-heading-4 font-medium">Siparişiniz Alındı!</h1>
            <p class="text-secondary/50 mt-3">
                Ödemeniz başarıyla tamamlandı. Sipariş numaranız:
            </p>
            <p class="text-heading-6 font-semibold text-primary-600 mt-2">{{ $orderNumber }}</p>
            <p class="text-tagline-2 text-secondary/40 mt-4">
                Siparişiniz en kısa sürede hazırlanıp kargoya verilecektir.
            </p>
            <div class="mt-8 flex gap-3 justify-center flex-wrap">
                <a href="{{ route('home') }}" class="btn btn-white btn-md"><span>Anasayfa</span></a>
                @auth
                    <a href="{{ route('account.orders') }}" class="btn btn-primary btn-md"><span>Siparişlerim</span></a>
                @endauth
            </div>
        </div>
    </div>
@endsection
