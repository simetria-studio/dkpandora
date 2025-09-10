<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout()
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

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        return view('orders.checkout', compact('cart', 'products', 'customGoldItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_username' => 'required|string|max:255',
            'payment_method' => 'required|in:stripe,paypal,pix',
        ]);

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

        $total = 0;
        // Calcular total dos produtos normais
        foreach ($products as $product) {
            $total += $product->price * $cart[$product->id];
        }

        // Calcular total dos itens de gold personalizado
        foreach ($customGoldItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $total,
            'game_username' => $request->game_username,
            'server_name' => 'Grand Fantasia Violet - Principal',
            'payment_method' => $request->payment_method,
            'status' => 'pending'
        ]);

        // Criar itens do pedido para produtos normais
        foreach ($products as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $cart[$product->id],
                'price' => $product->price
            ]);
        }

        // Criar itens do pedido para gold personalizado
        foreach ($customGoldItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => null, // Gold personalizado não tem produto associado
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'product_name' => $item['name'],
                'product_description' => $item['description'],
                'custom_data' => json_encode([
                    'type' => 'custom_gold',
                    'gold_amount' => $item['gold_amount']
                ])
            ]);
        }

        session()->forget('cart');

        // Redirecionar baseado no método de pagamento selecionado
        $paymentMethod = $request->input('payment_method');
        
        switch ($paymentMethod) {
            case 'paypal':
                return redirect()->route('paypal.process', $order)
                    ->with('success', 'Pedido criado! Agora você será redirecionado para o PayPal.');
            
            case 'pix':
                return redirect()->route('payments.pix', $order)
                    ->with('success', 'Pedido criado! Agora você será redirecionado para o pagamento PIX.');
            
            case 'stripe':
            default:
                return redirect()->route('payments.process', $order)
                    ->with('success', 'Pedido criado! Agora você será redirecionado para o pagamento.');
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function paymentSelection(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Este pedido não pode ser pago.');
        }

        return view('orders.payment-selection', compact('order'));
    }
}
