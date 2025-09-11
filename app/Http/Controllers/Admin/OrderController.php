<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('orderItems.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'game_username' => 'required|string|max:255',
            'server_name' => 'required|string|max:255',
        ]);

        $order->update($request->all());

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pedido atualizado com sucesso!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            \Log::info('UpdateStatus called', [
                'order_id' => $order->id,
                'status' => $request->status,
                'request_data' => $request->all()
            ]);

            $request->validate([
                'status' => 'required|in:pending,paid,delivered,cancelled'
            ]);

            $order->update(['status' => $request->status]);

            \Log::info('Order status updated successfully', [
                'order_id' => $order->id,
                'new_status' => $order->status
            ]);

            return redirect()->back()
                ->with('success', 'Status do pedido atualizado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Error updating order status', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao atualizar status: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pedido excluído com sucesso!');
    }
}
