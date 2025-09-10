<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar a página inicial
     */
    public function index()
    {
        // Buscar produtos em destaque (últimos 6 produtos)
        $featuredProducts = Product::latest()->take(6)->get();

        // Buscar produtos de gold (primeiros 4 produtos de gold)
        $goldProducts = Product::where('type', 'gold')->take(4)->get();

        // Buscar produtos mais vendidos (exemplo - você pode implementar lógica de vendas)
        $popularProducts = Product::inRandomOrder()->take(4)->get();

        return view('home', compact('featuredProducts', 'goldProducts', 'popularProducts'));
    }
}
