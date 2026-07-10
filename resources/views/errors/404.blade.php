@extends('layouts.app')

@section('title', 'Sayfa Bulunamadı - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-xl mx-auto px-5 pt-44 pb-10 text-center">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-10 relative overflow-hidden">
            <img src="{{ asset('images/glow-warm.png') }}" alt="" aria-hidden="true"
                 class="pointer-events-none select-none absolute -top-24 -right-24 w-[280px] opacity-40">
            <div class="relative">
                <p class="text-[80px] leading-none font-bold text-primary-500">404</p>
                <h1 class="text-heading-5 font-medium mt-3">Aradığınız sayfa bulunamadı</h1>
                <p class="text-secondary/50 mt-2 text-[15px]">
                    Sayfa taşınmış ya da kaldırılmış olabilir. Halılara göz atmaya ne dersiniz?
                </p>
                <div class="mt-8 flex gap-3 justify-center flex-wrap">
                    <a href="{{ route('home') }}" class="btn btn-white btn-md"><span>Anasayfa</span></a>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-md"><span>Ürünlere Göz At</span></a>
                </div>
            </div>
        </div>
    </div>
@endsection
