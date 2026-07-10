@extends('emails.layout')

@section('body')
    <h2 style="margin:0 0 8px; font-size:20px;">Siparişiniz yola çıktı! 📦</h2>
    <p style="margin:0 0 20px; color:#555; font-size:14px; line-height:1.6;">
        Merhaba {{ $order->name }}, {{ $order->order_number }} numaralı siparişiniz
        {{ $order->shipping_method === 'same_day' ? 'mağaza aracımızla adresinize doğru yola çıktı.' : 'kargoya verildi.' }}
    </p>

    @if($order->cargo_company)
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#cdf5f8; border-radius:12px; margin-bottom:20px;">
            <tr>
                <td style="padding:14px 16px; font-size:14px; line-height:1.8;">
                    <strong>Kargo Firması:</strong> {{ $order->cargo_company }}<br>
                    @if($order->tracking_number)
                        <strong>Takip Numarası:</strong> {{ $order->tracking_number }}
                    @endif
                </td>
            </tr>
        </table>
    @endif

    <p style="margin:0 0 20px; color:#555; font-size:14px;">
        Sipariş durumunuzu dilediğiniz zaman sitemizden sorgulayabilirsiniz.
    </p>

    <p style="margin:0;">
        <a href="{{ route('orders.track') }}" style="display:inline-block; background:#864ffe; color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:999px; font-size:14px; font-weight:bold;">
            Siparişimi Takip Et
        </a>
    </p>
@endsection
