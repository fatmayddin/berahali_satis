<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function legal(string $slug)
    {
        $pages = [
            'mesafeli-satis-sozlesmesi' => ['title' => 'Mesafeli Satış Sözleşmesi', 'view' => 'pages.legal.mesafeli-satis'],
            'iade-ve-degisim' => ['title' => 'İade ve Değişim Politikası', 'view' => 'pages.legal.iade'],
            'gizlilik-ve-kvkk' => ['title' => 'Gizlilik ve KVKK Aydınlatma Metni', 'view' => 'pages.legal.kvkk'],
            'on-bilgilendirme-formu' => ['title' => 'Ön Bilgilendirme Formu', 'view' => 'pages.legal.on-bilgilendirme'],
        ];

        abort_unless(isset($pages[$slug]), 404);

        return view($pages[$slug]['view'], ['title' => $pages[$slug]['title']]);
    }

    public function track()
    {
        return view('pages.track');
    }

    public function trackLookup(Request $request)
    {
        $data = $request->validate([
            'order_number' => 'required|string|max:50',
            'email' => 'required|email',
        ], [], ['order_number' => 'Sipariş No', 'email' => 'E-posta']);

        $order = \App\Models\Order::with('items')
            ->where('order_number', trim($data['order_number']))
            ->where('email', trim($data['email']))
            ->first();

        if (! $order) {
            return back()->withInput()->with('error', 'Bu bilgilerle eşleşen bir sipariş bulunamadı. Sipariş numaranızı ve e-posta adresinizi kontrol edin.');
        }

        return view('pages.track', compact('order'));
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ], [], [
            'name' => 'Ad Soyad',
            'email' => 'E-posta',
            'phone' => 'Telefon',
            'subject' => 'Konu',
            'message' => 'Mesaj',
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Mesajınız alındı. En kısa sürede size dönüş yapacağız.');
    }
}
