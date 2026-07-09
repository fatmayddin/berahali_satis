<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('site_title', 'Bera Halı'))</title>
    <meta name="description" content="@yield('meta_description', 'Bera Halı - Kaliteli halılar, uygun fiyatlar, kapınıza kadar teslimat.')">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f4f2fe', 100: '#ece8ff', 200: '#dcd4ff', 300: '#c3b1ff',
                            400: '#a585ff', 500: '#864ffe', 600: '#7c31f6', 700: '#6d1fe2', 800: '#5a19be',
                        },
                        secondary: '#1a1a1c',
                        accent: '#fcfcfc',
                        bg1: '#fcfcfd', bg2: '#f9fafb', bg3: '#f4f5f8', bg4: '#f0f2f6', bg12: '#eaeceb',
                        stroke1: '#dfe4eb', stroke2: '#e3e7ed', stroke3: '#d7dde5', stroke4: '#eceff4',
                        nsyellow: '#f9eb57', nsgreen: '#c6f56f', nsred: '#ffb9a2', nscyan: '#83e7ee',
                        nsgreenlight: '#e8fbc6', nscyanlight: '#cdf5f8', nsyellowlight: '#fdf7bc',
                    },
                    fontFamily: { sans: ['"Inter Tight"', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    fontSize: {
                        'heading-1': ['4.25rem', '110%'], 'heading-2': ['3.25rem', '120%'],
                        'heading-3': ['2.5rem', '120%'], 'heading-4': ['2rem', '130%'],
                        'heading-5': ['1.5rem', '130%'], 'heading-6': ['1.25rem', '130%'],
                        'tagline-1': ['1.125rem', '150%'], 'tagline-2': ['0.875rem', '150%'],
                    },
                    boxShadow: {
                        1: '0px 1px 2px 0px rgba(13,13,18,0.06)',
                        card: '0px 8px 30px -6px rgba(13,13,18,0.08)',
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        /* ==== NextSaaS btn ==== */
        .btn {
            position: relative; overflow: hidden; display: inline-block; text-align: center;
            border: 1px solid transparent; border-radius: 9999px; cursor: pointer; white-space: nowrap;
            transition: all .4s ease-in-out; font-weight: 400;
        }
        .btn:hover { transform: scale(1.02); }
        .btn > span { display: inline-block; transition: transform .3s ease-in-out; }
        .btn::before {
            content: '';
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='white' viewBox='0 0 256 256'%3E%3Cpath d='M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: center; background-size: contain;
            position: absolute; right: 0; top: 50%; transform: translateY(-50%);
            width: 0; height: 0; opacity: 0; transition: all .3s ease-in-out;
        }
        .btn:hover::before { width: 12px; height: 12px; opacity: 1; transform: translate(-16px, -50%); }
        .btn:hover > span { transform: translateX(-8px); }

        .btn-primary { background: #864ffe; border-color: #7c31f6; color: #fff; box-shadow: 0px 1px 2px 0px rgba(13,13,18,.06); }
        .btn-primary:hover { background: #7c31f6; }
        .btn-white { background: #fcfcfd; border-color: #d7dde5; color: #1a1a1c; box-shadow: 0px 1px 2px 0px rgba(13,13,18,.06); }
        .btn-white::before { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='%231a1a1c' viewBox='0 0 256 256'%3E%3Cpath d='M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z'%3E%3C/path%3E%3C/svg%3E"); }
        .btn-secondary { background: #1a1a1c; border-color: #000; color: #fff; box-shadow: 0px 1px 2px 0px rgba(13,13,18,.06); }

        .btn-md { padding: 10px 24px; font-size: .875rem; }
        .btn-lg { padding: 12px 32px; font-size: .9375rem; }
        .btn-xl { padding: 14px 36px; font-size: 1rem; }

        /* ==== NextSaaS badge ==== */
        .badge {
            display: inline-block; white-space: nowrap; border-radius: 9999px;
            padding: 6px 20px; font-size: .875rem; color: #1a1a1c; backdrop-filter: blur(2px);
        }
        .badge-yellow { background: #fdf7bc; }
        .badge-green { background: #e8fbc6; }
        .badge-cyan { background: #cdf5f8; }
        .badge-primary { background: #f0f2f6; color: #864ffe; }

        /* ==== inputs ==== */
        .input {
            width: 100%; background: #fff; border: 1px solid #d7dde5; border-radius: 12px;
            padding: 12px 20px; font-size: .9375rem; outline: none; transition: all .2s;
        }
        .input:focus { border-color: #864ffe; box-shadow: 0 0 0 3px rgba(134,79,254,.15); }
    </style>
</head>
<body class="text-secondary font-sans antialiased flex flex-col min-h-screen"
      style="background: linear-gradient(180deg, #f5f3fb 0%, #f4f5f8 480px);">

    {{-- Global dekoratif arka plan katmanı --}}
    <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden select-none" aria-hidden="true">
        {{-- Grenli gradient blob'lar (template görselleri) --}}
        <img src="{{ asset('images/glow-cyan.png') }}" alt="" class="absolute -top-32 -left-40 w-[480px] opacity-50">
        <img src="{{ asset('images/glow-warm.png') }}" alt="" class="absolute -top-24 -right-32 w-[520px] opacity-50">
        <img src="{{ asset('images/glow-soft.png') }}" alt="" class="absolute top-[38%] -left-56 w-[560px] opacity-25">
        <img src="{{ asset('images/glow-orb.png') }}" alt="" class="absolute top-[55%] -right-64 w-[620px] opacity-20">
        {{-- Nokta deseni (üstte yoğun, aşağı doğru kaybolur) --}}
        <div class="absolute inset-x-0 top-0 h-[560px]"
             style="background-image: radial-gradient(rgba(26,26,28,0.07) 1px, transparent 1px);
                    background-size: 26px 26px;
                    -webkit-mask-image: radial-gradient(ellipse 80% 100% at 50% 0%, black 30%, transparent 75%);
                    mask-image: radial-gradient(ellipse 80% 100% at 50% 0%, black 30%, transparent 75%);"></div>
    </div>

    {{-- Floating pill navbar --}}
    <header class="fixed top-4 left-0 right-0 z-50 px-4" x-data="{ open: false }">
        <div class="max-w-6xl mx-auto bg-white/90 backdrop-blur-md border border-stroke2 rounded-full shadow-1 px-5 py-2.5 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="pl-1 flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="{{ \App\Models\Setting::get('site_title', 'Bera Halı') }}"
                     class="h-9 md:h-10 w-auto">
            </a>

            <nav class="hidden lg:flex items-center">
                @php
                    $nav = [
                        ['route' => 'home', 'label' => 'Anasayfa', 'is' => 'home'],
                        ['route' => 'products.index', 'label' => 'Ürünler', 'is' => 'products.index'],
                        ['route' => 'products.campaigns', 'label' => 'Fırsatlar', 'is' => 'products.campaigns'],
                        ['route' => 'about', 'label' => 'Hakkımızda', 'is' => 'about'],
                        ['route' => 'contact', 'label' => 'İletişim', 'is' => 'contact'],
                    ];
                @endphp
                <ul class="flex items-center">
                    @foreach($nav as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center rounded-full border px-4 py-2 text-[15px] transition-all duration-200
                                      {{ request()->routeIs($item['is']) ? 'border-stroke2 text-secondary' : 'border-transparent text-secondary/60 hover:text-secondary hover:border-stroke2' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('account.orders') }}" class="hidden lg:flex items-center rounded-full border border-transparent hover:border-stroke2 px-4 py-2 text-[15px] text-secondary/60 hover:text-secondary transition-all">Hesabım</a>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:flex items-center rounded-full border border-transparent hover:border-stroke2 px-4 py-2 text-[15px] text-secondary/60 hover:text-secondary transition-all">Giriş Yap</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="btn btn-primary btn-md !flex items-center gap-2">
                    <span class="!flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                        </svg>
                        Sepet{{ ($cartCount ?? 0) > 0 ? ' ('.$cartCount.')' : '' }}
                    </span>
                </a>

                <button class="lg:hidden p-2" @click="open = !open">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobil menü --}}
        <div class="lg:hidden max-w-6xl mx-auto mt-2 bg-white border border-stroke2 rounded-3xl shadow-card px-6 py-4 space-y-1" x-show="open" x-cloak @click.outside="open = false">
            <a href="{{ route('home') }}" class="block py-2 text-secondary/70">Anasayfa</a>
            <a href="{{ route('products.index') }}" class="block py-2 text-secondary/70">Ürünler</a>
            <a href="{{ route('products.campaigns') }}" class="block py-2 text-secondary/70">Fırsatlar</a>
            <a href="{{ route('about') }}" class="block py-2 text-secondary/70">Hakkımızda</a>
            <a href="{{ route('contact') }}" class="block py-2 text-secondary/70">İletişim</a>
            @auth
                <a href="{{ route('account.orders') }}" class="block py-2 text-secondary/70">Hesabım</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="py-2 text-secondary/50">Çıkış</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-2 text-secondary/70">Giriş Yap</a>
            @endauth
        </div>
    </header>

    {{-- Bildirimler --}}
    @if(session('success') || session('error'))
        <div class="max-w-6xl mx-auto px-4 pt-28 -mb-16 w-full relative z-40">
            @if(session('success'))
                <div class="bg-nsgreenlight border border-nsgreen text-secondary rounded-2xl px-5 py-3.5">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-nsred/20 border border-nsred text-secondary rounded-2xl px-5 py-3.5">{{ session('error') }}</div>
            @endif
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer (koyu) --}}
    <footer class="relative z-0 overflow-hidden bg-secondary mt-24">
        <div class="pointer-events-none absolute -top-52 left-1/2 -translate-x-1/2 w-[700px] h-[400px] rounded-full opacity-25 blur-3xl"
             style="background: linear-gradient(135deg, #864ffe 0%, #23eed6 100%);"></div>
        <div class="max-w-6xl mx-auto px-5 relative">
            <div class="grid grid-cols-12 gap-x-0 gap-y-12 pt-16 pb-12">
                <div class="col-span-12 md:col-span-5">
                    <img src="{{ asset('images/logo-beyaz.png') }}" alt="{{ \App\Models\Setting::get('site_title', 'Bera Halı') }}"
                         class="h-11 w-auto">
                    <p class="text-accent/60 mt-4 mb-7 max-w-[306px] leading-relaxed">
                        {{ \App\Models\Setting::get('home_subline') }}
                    </p>
                    @if(\App\Models\Setting::get('instagram'))
                        <a href="{{ \App\Models\Setting::get('instagram') }}" target="_blank" class="inline-flex items-center gap-2 text-accent/60 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            Instagram
                        </a>
                    @endif
                </div>
                <div class="col-span-6 md:col-span-3">
                    <p class="text-accent font-medium mb-5">Hızlı Erişim</p>
                    <ul class="space-y-3 text-accent/60">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition">Ürünler</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">Hakkımızda</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">İletişim</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition">Sepetim</a></li>
                    </ul>
                </div>
                <div class="col-span-6 md:col-span-4">
                    <p class="text-accent font-medium mb-5">İletişim</p>
                    <ul class="space-y-3 text-accent/60">
                        <li>{{ \App\Models\Setting::get('phone') }}</li>
                        <li>{{ \App\Models\Setting::get('email') }}</li>
                        <li class="leading-relaxed">{{ \App\Models\Setting::get('address') }}</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-2 text-accent/40 text-sm py-5">
                <span>© {{ date('Y') }} {{ \App\Models\Setting::get('site_title', 'Bera Halı') }} — Tüm hakları saklıdır.</span>
                <span><a href="https://www.instagram.com/equinoxiaweb?igsh=MW54ZGtraWlwMmZyYQ==" class="text-accent/60 hover:text-white transition font-medium">Equinoxia Web</a> tarafından geliştirilmiştir</span>
            </div>
        </div>
    </footer>

    @include('components.faq-bot')

    @if(\App\Models\Setting::get('whatsapp'))
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp') }}?text={{ urlencode('Merhaba 👋 '.\App\Models\Setting::get('site_title', 'Bera Halı').' sitenizden yazıyorum, bilgi almak istiyorum.') }}"
           target="_blank" rel="noopener"
           class="fixed bottom-5 right-5 z-50 flex items-center gap-2.5 bg-green-500 hover:bg-green-600 text-white rounded-full py-3 px-4 sm:pr-5 shadow-card font-medium transition hover:scale-105" title="WhatsApp'tan Yazın">
            <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            <span class="hidden sm:inline">WhatsApp'tan Yazın 💬</span>
        </a>
    @endif
</body>
</html>
