@extends('layouts.app')

@section('title', 'Siparişlerim - '.\App\Models\Setting::get('site_title', 'Bera Halı'))

@section('content')
    <div class="max-w-4xl mx-auto px-5 pt-36">
        <div class="text-center mb-10">
            <span class="badge badge-primary mb-4">hesabım</span>
            <h1 class="text-heading-3 font-medium">Siparişlerim</h1>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button class="text-tagline-2 text-secondary/50 hover:text-secondary transition">Çıkış yap</button>
            </form>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white rounded-[24px] border border-stroke2 shadow-1 p-14 text-center">
                <p class="text-secondary/50">Henüz siparişiniz bulunmuyor.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mt-6"><span>Alışverişe Başla</span></a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <a href="{{ route('account.order-detail', $order->order_number) }}"
                       class="block bg-white rounded-[20px] border border-stroke2 shadow-1 hover:shadow-card p-5 transition-all">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <span class="font-semibold">{{ $order->order_number }}</span>
                                <span class="text-tagline-2 text-secondary/40 ml-2">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="badge !py-1 !px-3.5 !text-xs font-medium
                                    @switch($order->status)
                                        @case('paid') bg-nsgreenlight @break
                                        @case('pending') bg-nsyellowlight @break
                                        @case('failed') @case('cancelled') bg-nsred/30 @break
                                        @default bg-nscyanlight
                                    @endswitch">
                                    {{ $order->status_label }}
                                </span>
                                <span class="font-semibold">{{ number_format($order->total, 2, ',', '.') }} ₺</span>
                            </div>
                        </div>
                        <p class="text-tagline-2 text-secondary/50 mt-2">
                            {{ $order->items->pluck('product_name')->take(3)->implode(', ') }}{{ $order->items->count() > 3 ? '…' : '' }}
                        </p>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
