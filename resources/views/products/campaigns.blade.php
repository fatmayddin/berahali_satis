@extends('layouts.app')

@section('title', 'Fırsatlar - '.\App\Models\Setting::get('site_title', 'Bera Halı'))
@section('meta_description', 'Kampanyalı ve indirimli halılar - kaçırmayın!')

@section('content')
    {{-- Sayfa başlığı --}}
    <section class="pt-40 pb-10 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute top-0 -left-[4%] w-[280px] h-[280px] rounded-full opacity-30 blur-3xl"
             style="background: linear-gradient(135deg, #f9eb57 0%, #f99988 100%);"></div>
        <div class="pointer-events-none absolute top-[10%] -right-[4%] w-[240px] h-[240px] rounded-full opacity-25 blur-3xl"
             style="background: linear-gradient(135deg, #a585ff 0%, #ffc2ad 100%);"></div>
        <div class="max-w-6xl mx-auto px-5 relative">
            <span class="badge badge-yellow mb-4">kaçırılmayacak fiyatlar</span>
            <h1 class="text-heading-3 md:text-heading-2 font-medium">Fırsat Ürünleri</h1>
            <p class="text-secondary/60 text-tagline-1 max-w-2xl mx-auto mt-4">
                Özel kampanyalı ve son adetleri kalan ürünler. Bu fiyatlar stoklarla sınırlı — beğendiğinizi kaçırmayın.
            </p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-5">
        @if($products->isEmpty())
            <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-14 text-center text-secondary/50 max-w-xl mx-auto">
                Şu anda aktif fırsat ürünü bulunmuyor. Yakında yenileri eklenecek!
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-md mt-6 !flex w-fit mx-auto"><span>Tüm Ürünlere Göz At</span></a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
