<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('rarity')) {
            $query->where('rarity', $request->rarity);
        }

        $products = $query->paginate(12);

        // Buscar produtos em destaque
        $featuredProducts = Product::active()
                                  ->where('is_featured', true)
                                  ->orderBy('created_at', 'desc')
                                  ->take(6)
                                  ->get();

        return view('products.index', compact('products', 'featuredProducts'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
