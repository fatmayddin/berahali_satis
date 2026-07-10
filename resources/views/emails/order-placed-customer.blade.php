@extends('emails.layout')

@section('body')
    <h2 style="margin:0 0 8px; font-size:20px;">Siparişiniz alındı, teşekkürler! 🎉</h2>
    <p style="margin:0 0 20px; color:#555; font-size:14px; line-height:1.6;">
        Merhaba {{ $order->name }}, ödemeniz başarıyla tamamlandı. Siparişiniz en kısa sürede hazırlanıp
        {{ $order->shipping_method === 'same_day' ? 'mağaza aracımızla adresinize teslim edilecek' : 'kargoya verilecek' }}.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f8; border-radius:12px; padding:16px; margin-bottom:20px;">
        <tr>
            <td style="padding:14px 16px; font-size:14px;">
                <strong>Sipariş No:</strong> {{ $order->order_number }}<br>
                <strong>Tarih:</strong> {{ $order->created_at->format('d.m.Y H:i') }}<br>
                <strong>Teslimat:</strong> {{ $order->shipping_method_label }}
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; border-collapse:collapse;">
        @foreach($order->items as $item)
            <tr>
                <td style="padding:8px 0; border-bottom:1px solid #eceff4;">{{ $item->product_name }} × {{ $item->quantity }}</td>
                <td align="right" style="padding:8px 0; border-bottom:1px solid #eceff4; white-space:nowrap;">{{ number_format((float) $item->line_total, 2, ',', '.') }} ₺</td>
            </tr>
        @endforeach
        <tr>
            <td style="padding:8px 0; color:#777;">Kargo</td>
            <td align="right" style="padding:8px 0;">{{ $order->shipping_cost > 0 ? number_format((float) $order->shipping_cost, 2, ',', '.').' ₺' : 'Ücretsiz' }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0; font-weight:bold; font-size:16px;">Toplam</td>
            <td align="right" style="padding:8px 0; font-weight:bold; font-size:16px;">{{ number_format((float) $order->total, 2, ',', '.') }} ₺</td>
        </tr>
    </table>

    <p style="margin:20px 0 0;">
        <a href="{{ route('orders.track') }}" style="display:inline-block; background:#864ffe; color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:999px; font-size:14px; font-weight:bold;">
            Siparişimi Takip Et
        </a>
    </p>
@endsection
