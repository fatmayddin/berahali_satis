@extends('layouts.app')

@section('title', 'Ödeme Başarısız - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-xl mx-auto px-5 pt-40 text-center">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-10">
            <div class="w-16 h-16 mx-auto bg-nsred/30 rounded-full flex items-center justify-center mb-5">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 class="text-heading-4 font-medium">Ödeme Tamamlanamadı</h1>
            <p class="text-secondary/50 mt-3">
                {{ session('error', 'Ödeme sırasında bir sorun oluştu. Kartınızdan ücret çekilmediyse tekrar deneyebilirsiniz.') }}
            </p>
            <div class="mt-8 flex gap-3 justify-center flex-wrap">
                <a href="{{ route('cart.index') }}" class="btn btn-primary btn-md"><span>Sepete Dön</span></a>
                <a href="{{ route('contact') }}" class="btn btn-white btn-md"><span>Bize Ulaşın</span></a>
            </div>
        </div>
    </div>
@endsection
