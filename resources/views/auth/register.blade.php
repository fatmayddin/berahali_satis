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

            @if(config('services.google.client_id'))
                <div class="flex items-center gap-3 my-5">
                    <span class="flex-1 h-px bg-stroke2"></span>
                    <span class="text-tagline-2 text-secondary/40">veya</span>
                    <span class="flex-1 h-px bg-stroke2"></span>
                </div>
                <a href="{{ route('google.redirect') }}"
                   class="w-full flex items-center justify-center gap-3 border border-stroke2 hover:border-stroke3 hover:bg-bg2 rounded-full py-3 font-medium transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18a10.97 10.97 0 0 0 0 9.86l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Google ile Üye Ol
                </a>
            @endif

            <p class="text-tagline-2 text-secondary/50 text-center mt-6">
                Zaten üye misiniz?
                <a href="{{ route('login') }}" class="text-primary-600 font-medium hover:underline">Giriş Yapın</a>
            </p>
        </div>
    </div>
@endsection
