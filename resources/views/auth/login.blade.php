@extends('layouts.app')

@section('title', 'Giriş Yap - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-md mx-auto px-5 pt-40">
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-8">
            <div class="text-center mb-7">
                <span class="badge badge-primary mb-4">hesabım</span>
                <h1 class="text-heading-4 font-medium">Giriş Yap</h1>
            </div>

            @if($errors->any())
                <div class="bg-nsred/20 border border-nsred rounded-2xl px-4 py-3 mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <input type="email" name="email" value="{{ old('email') }}" placeholder="E-posta" required class="input">
                <input type="password" name="password" placeholder="Şifre" required class="input">
                <label class="flex items-center gap-2.5 text-tagline-2 text-secondary/60 cursor-pointer">
                    <input type="checkbox" name="remember" value="1" class="accent-primary-500 rounded">
                    Beni hatırla
                </label>
                <button type="submit" class="btn btn-primary btn-lg w-full"><span>Giriş Yap</span></button>
            </form>

            <p class="text-tagline-2 text-secondary/50 text-center mt-6">
                Hesabınız yok mu?
                <a href="{{ route('register') }}" class="text-primary-600 font-medium hover:underline">Üye Olun</a>
            </p>
        </div>
    </div>
@endsection
