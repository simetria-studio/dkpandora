<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Iniciar processo de pagamento
     */
    public function processPayment(Request $request, Order $order)
    {
        // Verificar se o pedido pertence ao usuário logado
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se o pedido já foi pago
        if ($order->status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('warning', 'Este pedido já foi pago.');
        }

        try {
            $paymentIntent = $this->stripeService->createPaymentIntent($order);

            return view('payments.process', compact('order', 'paymentIntent'));
        } catch (\Exception $e) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Erro ao processar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Confirmar pagamento
     */
    public function confirmPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        try {
            $paymentIntent = $this->stripeService->confirmPaymentIntent(
                $request->payment_intent_id,
                $request->payment_method_id
            );

            if ($paymentIntent->status === 'succeeded') {
                // Atualizar status do pedido
                $order->update([
                    'status' => 'paid',
                    'transaction_id' => $paymentIntent->id,
                ]);

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Pagamento realizado com sucesso!');
            } else {
                return redirect()->back()
                    ->with('error', 'Erro ao processar pagamento. Tente novamente.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao confirmar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Webhook do Stripe
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = $this->stripeService->processWebhook($payload, $signature, $endpointSecret);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSucceeded($event->data->object);
                    break;
                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailed($event->data->object);
                    break;
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Manipular pagamento bem-sucedido
     */
    protected function handlePaymentSucceeded($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order && $order->status !== 'paid') {
                $order->update([
                    'status' => 'paid',
                    'transaction_id' => $paymentIntent->id,
                ]);
            }
        }
    }

    /**
     * Manipular pagamento falhado
     */
    protected function handlePaymentFailed($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'status' => 'failed',
                ]);
            }
        }
    }
}
