<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        // Separar produtos normais de gold personalizado
        $productIds = [];
        $customGoldItems = [];

        foreach ($cart as $key => $item) {
            if (is_array($item) && isset($item['type']) && $item['type'] === 'custom_gold') {
                $customGoldItems[$key] = $item;
            } else {
                $productIds[] = $key;
            }
        }

        $products = Product::whereIn('id', $productIds)->get();

        return view('cart.index', compact('cart', 'products', 'customGoldItems'));
    }

    public function add(Request $request, $product)
    {
        $cart = session()->get('cart', []);

        // Verificar se é uma compra de gold personalizada
        if ($request->has('custom_gold') && $request->input('custom_gold')) {
            $goldAmount = $request->input('gold_amount', 10000);
            $pricePer1000Gold = \App\Models\Setting::get('gold_price_per_1000', 0.12);
            $totalPrice = ($goldAmount / 1000) * $pricePer1000Gold;

            // Criar um item de gold personalizado
            $customGoldId = 'custom_gold_' . time();
            $cart[$customGoldId] = [
                'type' => 'custom_gold',
                'name' => number_format($goldAmount, 0, ',', '.') . ' Gold',
                'description' => 'Gold personalizado para Grand Fantasia Violet',
                'price' => $totalPrice,
                'quantity' => 1,
                'gold_amount' => $goldAmount,
                'image' => 'https://via.placeholder.com/300x200/FFD700/000000?text=Gold'
            ];

            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Gold personalizado adicionado ao carrinho!');
        }

        // Comportamento normal para produtos
        $product = Product::findOrFail($product);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$product->id])) {
            $cart[$product->id] += $quantity;
        } else {
            $cart[$product->id] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function remove(Request $request, $product)
    {
        $cart = session()->get('cart', []);

        // Verificar se é um item de gold personalizado
        if (isset($cart[$product])) {
            unset($cart[$product]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Item removido do carrinho!');
        }

        // Comportamento normal para produtos
        $product = Product::findOrFail($product);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produto removido do carrinho!');
    }

    public function update(Request $request, $product)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        // Verificar se é um item de gold personalizado
        if (isset($cart[$product]) && is_array($cart[$product])) {
            if ($quantity > 0) {
                $cart[$product]['quantity'] = $quantity;
            } else {
                unset($cart[$product]);
            }
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Carrinho atualizado!');
        }

        // Comportamento normal para produtos
        $product = Product::findOrFail($product);

        if ($quantity > 0) {
            $cart[$product->id] = $quantity;
        } else {
            unset($cart[$product->id]);
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Carrinho atualizado!');
    }
}
