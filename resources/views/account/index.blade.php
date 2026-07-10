@extends('layouts.app')

@section('title', 'Hesabım - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-4xl mx-auto px-5 pt-36">
        <div class="text-center mb-10">
            <h1 class="text-heading-3 font-medium">Hesabım</h1>
            <p class="text-secondary/50 mt-2">Merhaba, {{ $user->name }} 👋</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="text-tagline-2 text-secondary/50 hover:text-secondary transition">Çıkış yap</button>
            </form>
        </div>

        @if($errors->any())
            <div class="bg-nsred/20 border border-nsred rounded-2xl px-5 py-4 mb-6 text-sm">
                <ul class="list-disc ml-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">

            {{-- Profil ve adres bilgileri --}}
            <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-7">
                <h2 class="text-heading-6 font-medium mb-5">Profil ve Adres Bilgilerim</h2>
                <form method="POST" action="{{ route('account.profile') }}" class="space-y-4">
                    @csrf @method('PATCH')
                    <div>
                        <label class="text-tagline-2 text-secondary/50 block mb-1">Ad Soyad</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input">
                    </div>
                    <div>
                        <label class="text-tagline-2 text-secondary/50 block mb-1">E-posta</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input">
                    </div>
                    <div>
                        <label class="text-tagline-2 text-secondary/50 block mb-1">Telefon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="05xx xxx xx xx" class="input">
                    </div>
                    <div>
                        <label class="text-tagline-2 text-secondary/50 block mb-1">Adres</label>
                        <textarea name="address" rows="2" placeholder="Teslimat adresiniz" class="input !rounded-2xl">{{ old('address', $user->address) }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-tagline-2 text-secondary/50 block mb-1">İl</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" class="input">
                        </div>
                        <div>
                            <label class="text-tagline-2 text-secondary/50 block mb-1">İlçe</label>
                            <input type="text" name="district" value="{{ old('district', $user->district) }}" class="input">
                        </div>
                    </div>
                    <p class="text-tagline-2 text-secondary/40">Adres bilgileriniz ödeme adımında otomatik doldurulur.</p>
                    <button type="submit" class="btn btn-primary btn-md"><span>Kaydet</span></button>
                </form>
            </div>

            <div class="space-y-6">
                {{-- Şifre --}}
                <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-7">
                    <h2 class="text-heading-6 font-medium mb-5">Şifre Değiştir</h2>
                    @if($user->google_id && ! $user->password)
                        <p class="text-tagline-2 text-secondary/50 mb-4">Google ile giriş yapıyorsunuz; dilerseniz bir şifre belirleyebilirsiniz.</p>
                    @endif
                    <form method="POST" action="{{ route('account.password') }}" class="space-y-4">
                        @csrf @method('PATCH')
                        <input type="password" name="current_password" placeholder="Mevcut şifre{{ $user->google_id ? ' (Google üyeliğinde boş bırakılabilir)' : '' }}" class="input">
                        <input type="password" name="password" placeholder="Yeni şifre (en az 8 karakter)" required class="input">
                        <input type="password" name="password_confirmation" placeholder="Yeni şifre tekrar" required class="input">
                        <button type="submit" class="btn btn-white btn-md"><span>Şifreyi Güncelle</span></button>
                    </form>
                </div>

                {{-- Son siparişler --}}
                <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-7">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-heading-6 font-medium">Son Siparişlerim</h2>
                        <a href="{{ route('account.orders') }}" class="text-tagline-2 text-primary-600 hover:underline">Tümü →</a>
                    </div>
                    @forelse($recentOrders as $order)
                        <a href="{{ route('account.order-detail', $order->order_number) }}"
                           class="flex items-center justify-between gap-2 py-2.5 border-b border-stroke4 last:border-0 group">
                            <span>
                                <span class="font-medium text-[15px] group-hover:text-primary-600 transition">{{ $order->order_number }}</span>
                                <span class="text-tagline-2 text-secondary/40 block">{{ $order->created_at->format('d.m.Y') }} · {{ $order->status_label }}</span>
                            </span>
                            <span class="font-semibold whitespace-nowrap">{{ number_format($order->total, 2, ',', '.') }} ₺</span>
                        </a>
                    @empty
                        <p class="text-tagline-2 text-secondary/40">Henüz siparişiniz yok.
                            <a href="{{ route('products.index') }}" class="text-primary-600 hover:underline">Alışverişe başlayın →</a>
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
