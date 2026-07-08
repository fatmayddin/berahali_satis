@extends('layouts.app')

@section('title', 'Üye Ol - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-md mx-auto px-5 pt-40">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-8">
            <div class="text-center mb-7">
                <span class="badge badge-green mb-4">yeni hesap</span>
                <h1 class="text-heading-4 font-medium">Üye Ol</h1>
            </div>

            @if($errors->any())
                <div class="bg-nsred/20 border border-nsred rounded-2xl px-4 py-3 mb-4 text-sm">
                    <ul class="list-disc ml-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ad Soyad" required class="input">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="E-posta" required class="input">
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Telefon" class="input">
                <input type="password" name="password" placeholder="Şifre (en az 8 karakter)" required class="input">
                <input type="password" name="password_confirmation" placeholder="Şifre Tekrar" required class="input">
                <button type="submit" class="btn btn-primary btn-lg w-full"><span>Üye Ol</span></button>
            </form>

            <p class="text-tagline-2 text-secondary/50 text-center mt-6">
                Zaten üye misiniz?
                <a href="{{ route('login') }}" class="text-primary-600 font-medium hover:underline">Giriş Yapın</a>
            </p>
        </div>
    </div>
@endsection
