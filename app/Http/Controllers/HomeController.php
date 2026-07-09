<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'sliders' => Slider::active()->get(),
            'featured' => Product::active()->where('is_featured', true)->latest()->take(8)->get(),
            'latest' => Product::active()->latest()->take(8)->get(),
            'categories' => Category::active()->get(),
            'cutProduct' => Product::active()->where('is_cut', true)->latest()->first(),
        ]);
    }
}
