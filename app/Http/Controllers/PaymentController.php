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
            'payment_intent_id' => 'required|string',
        ]);

        try {
            // Sanitizar dados de entrada
            $paymentIntentId = $this->sanitizePaymentData($request->payment_intent_id);
            $paymentMethodId = $this->sanitizePaymentData($request->payment_method_id);

            if (empty($paymentIntentId) || empty($paymentMethodId)) {
                throw new \Exception('Dados de pagamento inválidos');
            }

            $paymentIntent = $this->stripeService->confirmPaymentIntent(
                $paymentIntentId,
                $paymentMethodId
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
     * Sanitizar dados de pagamento
     */
    private function sanitizePaymentData($data)
    {
        // Se for array, pegar o primeiro elemento
        if (is_array($data)) {
            $data = $data[0] ?? '';
        }

        // Converter para string e aplicar trim
        $data = trim((string) $data);

        // Validar se não está vazio
        if (empty($data)) {
            throw new \Exception('Dados de pagamento inválidos');
        }

        return $data;
    }

    /**
     * Processar pagamento PIX
     */
    public function processPixPayment(Request $request, Order $order)
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
            $paymentIntent = $this->stripeService->createPixPaymentIntent($order);
            $pixData = $this->stripeService->getPixData($paymentIntent->id);

            return view('payments.pix', compact('order', 'paymentIntent', 'pixData'));
        } catch (\Exception $e) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Erro ao gerar PIX: ' . $e->getMessage());
        }
    }

    /**
     * Verificar status do PIX
     */
    public function checkPixStatus(Request $request, Order $order)
    {
        try {
            $paymentIntentId = $request->input('payment_intent_id');

            if (empty($paymentIntentId)) {
                return response()->json(['error' => 'ID do Payment Intent não fornecido'], 400);
            }

            $paymentIntent = $this->stripeService->retrievePaymentIntent($paymentIntentId);

            return response()->json([
                'status' => $paymentIntent->status,
                'paid' => $paymentIntent->status === 'succeeded',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
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

        // Validar dados do webhook
        if (empty($payload)) {
            return response()->json(['error' => 'Payload vazio'], 400);
        }

        if (empty($signature)) {
            return response()->json(['error' => 'Assinatura não fornecida'], 400);
        }

        if (empty($endpointSecret)) {
            return response()->json(['error' => 'Webhook secret não configurado'], 400);
        }

        try {
            $event = $this->stripeService->processWebhook($payload, $signature, $endpointSecret);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSucceeded($event->data->object);
                    break;
                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailed($event->data->object);
                    break;
                case 'payment_intent.processing':
                    // PIX pode ficar em processamento por alguns minutos
                    $this->handlePaymentProcessing($event->data->object);
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
     * Manipular pagamento em processamento
     */
    protected function handlePaymentProcessing($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order && $order->status !== 'paid') {
                $order->update([
                    'status' => 'processing',
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
