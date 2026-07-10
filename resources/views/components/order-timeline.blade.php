@props(['order'])

@php
    $steps = [
        'paid' => 'Ödeme Alındı',
        'preparing' => 'Hazırlanıyor',
        'shipped' => $order->shipping_method === 'same_day' ? 'Yola Çıktı' : 'Kargoya Verildi',
        'delivered' => 'Teslim Edildi',
    ];
    $orderList = array_keys($steps);
    $currentIndex = array_search($order->status, $orderList);
    $isProblem = in_array($order->status, ['failed', 'cancelled']);
    $isPending = $order->status === 'pending';
    $progress = $currentIndex === false ? 0 : ($currentIndex / (count($steps) - 1)) * 100;
@endphp

@if($isProblem)
    <div class="bg-nsred/20 border border-nsred rounded-2xl px-5 py-4 text-sm">
        <span class="font-semibold">{{ $order->status_label }}.</span>
        {{ $order->status === 'failed' ? 'Ödeme tamamlanamadı; kartınızdan ücret çekilmediyse tekrar sipariş verebilirsiniz.' : 'Bu sipariş iptal edilmiştir.' }}
        Sorularınız için bizimle iletişime geçebilirsiniz.
    </div>
@elseif($isPending)
    <div class="bg-nsyellowlight border border-nsyellow rounded-2xl px-5 py-4 text-sm">
        <span class="font-semibold">Ödeme Bekleniyor.</span> Ödemeniz henüz tamamlanmadı.
    </div>
@else
    <div class="relative px-4">
        {{-- Çizgi --}}
        <div class="absolute top-4 left-[12.5%] right-[12.5%] h-0.5 bg-stroke2"></div>
        <div class="absolute top-4 left-[12.5%] h-0.5 bg-green-500 transition-all" style="width: calc({{ $progress }}% * 0.75);"></div>

        <div class="relative grid grid-cols-4">
            @foreach($steps as $key => $label)
                @php
                    $stepIndex = array_search($key, $orderList);
                    $done = $currentIndex !== false && $stepIndex <= $currentIndex;
                @endphp
                <div class="flex flex-col items-center text-center">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center
                        {{ $done ? 'bg-green-500 text-white' : 'bg-white text-secondary/30 border border-stroke2' }}">
                        @if($done)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        @else
                            <span class="text-xs font-medium">{{ $stepIndex + 1 }}</span>
                        @endif
                    </span>
                    <span class="text-[11px] sm:text-xs mt-2 {{ $done ? 'text-secondary font-medium' : 'text-secondary/40' }}">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    </div>

    @if($order->cargo_company && in_array($order->status, ['shipped', 'delivered']))
        <div class="mt-6 bg-nscyanlight/60 border border-nscyan rounded-2xl px-5 py-3.5 text-sm flex flex-wrap items-center gap-x-6 gap-y-1">
            <span><span class="text-secondary/50">Kargo:</span> <strong>{{ $order->cargo_company }}</strong></span>
            @if($order->tracking_number)
                <span><span class="text-secondary/50">Takip No:</span> <strong class="font-mono">{{ $order->tracking_number }}</strong></span>
            @endif
        </div>
    @endif
@endif
