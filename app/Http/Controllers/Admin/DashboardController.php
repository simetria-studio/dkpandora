<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('total_amount'),
            'total_products' => Product::count(),
            'total_users' => User::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
        ];

        // Pedidos recentes
        $recent_orders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Produtos mais vendidos
        $top_products = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->whereNotNull('order_items.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Vendas por mês (últimos 6 meses)
        $monthly_sales = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'top_products', 'monthly_sales'));
    }

    public function salesReport()
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.reports.sales', compact('orders'));
    }

    public function productsReport()
    {
        $products = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->paginate(20);

        return view('admin.reports.products', compact('products'));
    }
}
