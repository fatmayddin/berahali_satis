<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('account.index', [
            'user' => $user,
            'recentOrders' => $user->orders()->with('items')->latest()->take(3)->get(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
        ], [], [
            'name' => 'Ad Soyad',
            'email' => 'E-posta',
            'phone' => 'Telefon',
            'address' => 'Adres',
            'city' => 'İl',
            'district' => 'İlçe',
        ]);

        $user->update($data);

        return back()->with('success', 'Bilgileriniz güncellendi.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => $user->google_id && ! $request->filled('current_password') ? 'nullable' : 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.current_password' => 'Mevcut şifreniz hatalı.',
        ], [
            'current_password' => 'Mevcut Şifre',
            'password' => 'Yeni Şifre',
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Şifreniz güncellendi.');
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->with('items')->latest()->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber)
    {
        $order = Auth::user()->orders()->with('items.product')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('account.order-detail', compact('order'));
    }
}
