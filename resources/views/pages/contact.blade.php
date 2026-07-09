@extends('layouts.app')

@section('title', 'İletişim - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <section class="pt-40 pb-10 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute top-0 -right-[4%] w-[260px] h-[260px] rounded-full opacity-25 blur-3xl"
             style="background: linear-gradient(135deg, #a585ff 0%, #ffc2ad 100%);"></div>
        <div class="max-w-6xl mx-auto px-5 relative">
            <span class="badge badge-yellow mb-4">Bize Ulaşın</span>
            <h1 class="text-heading-3 md:text-heading-2 font-medium">İletişim</h1>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-5 grid md:grid-cols-2 gap-6">
        {{-- İletişim bilgileri --}}
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-8 h-fit">
            <h2 class="text-heading-6 font-medium mb-6">İletişim Bilgilerimiz</h2>
            <ul class="space-y-5">
                <li class="flex items-start gap-4">
                    <span class="w-11 h-11 bg-nsyellowlight rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                    </span>
                    <div>
                        <p class="text-tagline-2 text-secondary/50">Telefon</p>
                        <p class="font-medium">{{ \App\Models\Setting::get('phone') }}</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <span class="w-11 h-11 bg-nsgreenlight rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    </span>
                    <div>
                        <p class="text-tagline-2 text-secondary/50">E-posta</p>
                        <p class="font-medium">{{ \App\Models\Setting::get('email') }}</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <span class="w-11 h-11 bg-nscyanlight rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    </span>
                    <div>
                        <p class="text-tagline-2 text-secondary/50">Adres</p>
                        <p class="font-medium leading-relaxed">{{ \App\Models\Setting::get('address') }}</p>
                    </div>
                </li>
                @if(\App\Models\Setting::get('whatsapp'))
                    <li class="flex items-start gap-4">
                        <span class="w-11 h-11 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </span>
                        <div>
                            <p class="text-tagline-2 text-secondary/50">WhatsApp 💬</p>
                            <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp') }}?text={{ urlencode('Merhaba 👋 bilgi almak istiyorum.') }}"
                               target="_blank" rel="noopener" class="font-medium text-green-600 hover:underline">
                                Hemen mesaj gönderin →
                            </a>
                        </div>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-8">
            <h2 class="text-heading-6 font-medium mb-6">Bize Yazın</h2>

            @if($errors->any())
                <div class="bg-nsred/20 border border-nsred rounded-2xl px-4 py-3 mb-4 text-sm">
                    <ul class="list-disc ml-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ad Soyad *" required class="input">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="E-posta *" required class="input">
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Telefon" class="input">
                    <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Konu" class="input">
                </div>
                <textarea name="message" rows="5" placeholder="Mesajınız *" required class="input !rounded-2xl">{{ old('message') }}</textarea>
                <button type="submit" class="btn btn-primary btn-lg"><span>Gönder</span></button>
            </form>
        </div>
    </div>
@endsection
