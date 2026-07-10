@extends('emails.layout')

@section('body')
    <h2 style="margin:0 0 8px; font-size:20px;">Yeni sipariş geldi! 🛒</h2>
    <p style="margin:0 0 20px; color:#555; font-size:14px;">
        Ödemesi iyzico tarafından onaylanan yeni bir sipariş var.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f8; border-radius:12px; margin-bottom:20px;">
        <tr>
            <td style="padding:14px 16px; font-size:14px; line-height:1.8;">
                <strong>Sipariş No:</strong> {{ $order->order_number }}<br>
                <strong>Müşteri:</strong> {{ $order->name }} · {{ $order->phone }}<br>
                <strong>E-posta:</strong> {{ $order->email }}<br>
                <strong>Adres:</strong> {{ $order->address }} {{ $order->district }} / {{ $order->city }}<br>
                <strong>Teslimat:</strong> {{ $order->shipping_method_label }}<br>
                @if($order->note)
                    <strong>Not:</strong> {{ $order->note }}<br>
                @endif
                <strong>İyzico Ödeme No:</strong> {{ $order->payment_id ?? '-' }}
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; border-collapse:collapse;">
        @foreach($order->items as $item)
            <tr>
                <td style="padding:8px 0; border-bottom:1px solid #eceff4;">{{ $item->product_name }} ({{ $item->product_code }}) × {{ $item->quantity }}</td>
                <td align="right" style="padding:8px 0; border-bottom:1px solid #eceff4; white-space:nowrap;">{{ number_format((float) $item->line_total, 2, ',', '.') }} ₺</td>
            </tr>
        @endforeach
        <tr>
            <td style="padding:8px 0; font-weight:bold; font-size:16px;">Toplam</td>
            <td align="right" style="padding:8px 0; font-weight:bold; font-size:16px;">{{ number_format((float) $order->total, 2, ',', '.') }} ₺</td>
        </tr>
    </table>

    <p style="margin:20px 0 0;">
        <a href="{{ url('/admin/orders') }}" style="display:inline-block; background:#1a1a1c; color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:999px; font-size:14px; font-weight:bold;">
            Yönetim Panelinde Aç
        </a>
    </p>
@endsection
