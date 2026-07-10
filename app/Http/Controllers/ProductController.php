<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        // Arama (ürün adı, kodu veya açıklama)
        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('code', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        // Kategori filtresi
        if ($request->filled('kategori')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->kategori));
        }

        // m2 aralığı
        if ($request->filled('m2_min')) {
            $query->where('m2', '>=', (float) $request->m2_min);
        }
        if ($request->filled('m2_max')) {
            $query->where('m2', '<=', (float) $request->m2_max);
        }

        // Fiyat aralığı (indirimli fiyat varsa onun, kesme halıda m² fiyatının üzerinden)
        $priceExpr = 'COALESCE(discount_price, total_price, price_per_m2)';
        if ($request->filled('fiyat_min')) {
            $query->whereRaw("$priceExpr >= ?", [(float) $request->fiyat_min]);
        }
        if ($request->filled('fiyat_max')) {
            $query->whereRaw("$priceExpr <= ?", [(float) $request->fiyat_max]);
        }

        // Sadece indirimliler
        if ($request->boolean('indirimli')) {
            $query->whereNotNull('discount_price')->whereColumn('discount_price', '<', 'total_price');
        }

        // Sıralama
        $query = match ($request->get('sirala')) {
            'fiyat-artan' => $query->orderByRaw("$priceExpr asc"),
            'fiyat-azalan' => $query->orderByRaw("$priceExpr desc"),
            'm2-artan' => $query->orderBy('m2', 'asc'),
            'm2-azalan' => $query->orderBy('m2', 'desc'),
            'eski' => $query->oldest(),
            default => $query->latest(),
        };

        return view('products.index', [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Category::active()->withCount('products')->get(),
        ]);
    }

    /** Fırsatlar: kampanyalı / hızlı satılması istenen ürünler */
    public function campaigns()
    {
        $products = Product::active()
            ->where('is_campaign', true)
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('products.campaigns', compact('products'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->increment('views');

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
