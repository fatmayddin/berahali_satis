<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
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
