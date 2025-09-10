<?php

namespace App\Services;

use App\Models\Order;
use PaypalServerSdkLib\PaypalServerSdkClient;
use PaypalServerSdkLib\Controllers\OrdersController;
use PaypalServerSdkLib\Exceptions\ErrorException;
use PaypalServerSdkLib\Http\ApiResponse;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $client;
    protected $ordersController;

    public function __construct()
    {
        $this->client = app('paypal');
        $this->ordersController = $this->client->getOrdersController();
    }

    /**
     * Criar um pedido PayPal para o pedido
     */
    public function createOrder(Order $order)
    {
        try {
            Log::info('PayPal Service: Iniciando criação de pedido', [
                'order_id' => $order->id,
                'total_amount' => $order->total_amount,
                'game_username' => $order->game_username
            ]);

            $options = [
                'body' => [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => (string) $order->id,
                        'description' => "Pedido #{$order->id} - " . ($order->game_username ?? 'Cliente'),
                        'custom_id' => (string) $order->id,
                        'amount' => [
                            'currency_code' => 'BRL',
                            'value' => number_format($order->total_amount, 2, '.', '')
                        ]
                    ]],
                    'application_context' => [
                        'return_url' => route('paypal.success', $order),
                        'cancel_url' => route('paypal.cancel', $order),
                        'brand_name' => config('app.name'),
                        'landing_page' => 'LOGIN',
                        'user_action' => 'PAY_NOW'
                    ]
                ],
                'prefer' => 'return=representation'
            ];

            Log::info('PayPal Service: Opções do pedido preparadas', [
                'order_id' => $order->id,
                'options' => $options
            ]);

            // Verificar se o controller está disponível
            if (!$this->ordersController) {
                Log::error('PayPal Service: OrdersController não disponível');
                throw new \Exception('Controller do PayPal não disponível');
            }

            Log::info('PayPal Service: Chamando API do PayPal para criar pedido', [
                'order_id' => $order->id
            ]);

            $response = $this->ordersController->createOrder($options);

            Log::info('PayPal Service: Resposta da API recebida', [
                'order_id' => $order->id,
                'response_status' => $response->getStatusCode() ?? 'N/A',
                'response_body' => $response->getBody() ?? 'N/A'
            ]);

            return $response;
        } catch (ErrorException $e) {
            Log::error('PayPal Service: Erro da API do PayPal', [
                'order_id' => $order->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode() ?? 'N/A',
                'error_trace' => $e->getTraceAsString()
            ]);

            throw new \Exception('Erro ao criar pedido PayPal: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('PayPal Service: Erro inesperado', [
                'order_id' => $order->id,
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e),
                'error_trace' => $e->getTraceAsString()
            ]);

            throw new \Exception('Erro inesperado ao criar pedido PayPal: ' . $e->getMessage());
        }
    }

    /**
     * Capturar pagamento após aprovação do usuário
     */
    public function captureOrder($orderId)
    {
        try {
            $options = [
                'id' => $orderId
            ];

            $response = $this->ordersController->captureOrder($options);
            return $response;
        } catch (ErrorException $e) {
            throw new \Exception('Erro ao capturar pedido PayPal: ' . $e->getMessage());
        }
    }

    /**
     * Recuperar informações de um pedido
     */
    public function getOrder($orderId)
    {
        try {
            $options = [
                'id' => $orderId
            ];

            $response = $this->ordersController->getOrder($options);
            return $response;
        } catch (ErrorException $e) {
            throw new \Exception('Erro ao recuperar pedido PayPal: ' . $e->getMessage());
        }
    }

    /**
     * Verificar status de um pedido
     */
    public function getOrderStatus($orderId)
    {
        try {
            $order = $this->getOrder($orderId);
            return $order->getBody()->status;
        } catch (ErrorException $e) {
            throw new \Exception('Erro ao verificar status do pedido: ' . $e->getMessage());
        }
    }

    /**
     * Processar webhook do PayPal
     */
    public function processWebhook($payload)
    {
        try {
            $eventType = $payload['event_type'] ?? null;
            $resource = $payload['resource'] ?? null;

            if (!$eventType || !$resource) {
                throw new \Exception('Payload do webhook inválido');
            }

            switch ($eventType) {
                case 'PAYMENT.CAPTURE.COMPLETED':
                    return $this->handlePaymentCompleted($resource);

                case 'PAYMENT.CAPTURE.DENIED':
                    return $this->handlePaymentDenied($resource);

                case 'PAYMENT.CAPTURE.PENDING':
                    return $this->handlePaymentPending($resource);

                case 'CHECKOUT.ORDER.APPROVED':
                    return $this->handleOrderApproved($resource);

                case 'CHECKOUT.ORDER.COMPLETED':
                    return $this->handleOrderCompleted($resource);

                default:
                    return ['status' => 'ignored', 'message' => 'Evento não processado'];
            }
        } catch (\Exception $e) {
            throw new \Exception('Erro ao processar webhook: ' . $e->getMessage());
        }
    }

    /**
     * Processar pagamento completado
     */
    protected function handlePaymentCompleted($resource)
    {
        $paymentId = $resource['id'] ?? null;
        $orderId = $resource['custom_id'] ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'status' => 'completed',
                    'payment_method' => 'paypal',
                    'payment_id' => $paymentId
                ]);
            }
        }

        return [
            'status' => 'success',
            'message' => 'Pagamento processado com sucesso',
            'payment_id' => $paymentId,
            'order_id' => $orderId
        ];
    }

    /**
     * Processar pagamento negado
     */
    protected function handlePaymentDenied($resource)
    {
        $paymentId = $resource['id'] ?? null;
        $orderId = $resource['custom_id'] ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'status' => 'failed',
                    'payment_method' => 'paypal',
                    'payment_id' => $paymentId
                ]);
            }
        }

        return [
            'status' => 'failed',
            'message' => 'Pagamento negado',
            'payment_id' => $paymentId,
            'order_id' => $orderId
        ];
    }

    /**
     * Processar pagamento pendente
     */
    protected function handlePaymentPending($resource)
    {
        $paymentId = $resource['id'] ?? null;
        $orderId = $resource['custom_id'] ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'status' => 'pending',
                    'payment_method' => 'paypal',
                    'payment_id' => $paymentId
                ]);
            }
        }

        return [
            'status' => 'pending',
            'message' => 'Pagamento pendente',
            'payment_id' => $paymentId,
            'order_id' => $orderId
        ];
    }

    /**
     * Processar pedido aprovado
     */
    protected function handleOrderApproved($resource)
    {
        $paypalOrderId = $resource['id'] ?? null;
        $orderId = $resource['custom_id'] ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'status' => 'pending',
                    'payment_method' => 'paypal',
                    'payment_id' => $paypalOrderId
                ]);
            }
        }

        return [
            'status' => 'approved',
            'message' => 'Pedido aprovado pelo usuário',
            'paypal_order_id' => $paypalOrderId,
            'order_id' => $orderId
        ];
    }

    /**
     * Processar pedido completado
     */
    protected function handleOrderCompleted($resource)
    {
        $paypalOrderId = $resource['id'] ?? null;
        $orderId = $resource['custom_id'] ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'status' => 'completed',
                    'payment_method' => 'paypal',
                    'payment_id' => $paypalOrderId
                ]);
            }
        }

        return [
            'status' => 'completed',
            'message' => 'Pedido completado',
            'paypal_order_id' => $paypalOrderId,
            'order_id' => $orderId
        ];
    }
}
