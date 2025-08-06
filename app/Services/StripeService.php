<?php

namespace App\Services;

use App\Models\Order;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Stripe\Webhook;

class StripeService
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = app('stripe');
    }

    /**
     * Criar um Payment Intent para o pedido
     */
    public function createPaymentIntent(Order $order, $paymentMethod = null)
    {
        try {
            $paymentIntentData = [
                'amount' => (int) ($order->total_amount * 100), // Stripe usa centavos
                'currency' => 'brl',
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never'
                ],
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'user_id' => (string) $order->user_id,
                    'game_username' => (string) ($order->game_username ?? ''),
                ],
                'description' => "Pedido #{$order->id} - " . ($order->game_username ?? 'Cliente'),
            ];

            if ($paymentMethod) {
                $paymentIntentData['payment_method'] = $paymentMethod;
                $paymentIntentData['confirm'] = true;
                $paymentIntentData['return_url'] = route('orders.show', $order);
            }

            $paymentIntent = $this->stripe->paymentIntents->create($paymentIntentData);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception('Erro ao processar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Confirmar um Payment Intent
     */
    public function confirmPaymentIntent($paymentIntentId, $paymentMethodId)
    {
        try {
            // Sanitizar dados de entrada
            $paymentIntentId = $this->sanitizePaymentData($paymentIntentId);
            $paymentMethodId = $this->sanitizePaymentData($paymentMethodId);

            // Validar IDs
            if (empty($paymentIntentId) || !is_string($paymentIntentId)) {
                throw new \Exception('ID do Payment Intent inválido');
            }

            if (empty($paymentMethodId) || !is_string($paymentMethodId)) {
                throw new \Exception('ID do método de pagamento inválido');
            }

            // Forçar conversão para string e validar
            $paymentIntentId = $this->forceString($paymentIntentId);
            $paymentMethodId = $this->forceString($paymentMethodId);

            // Confirmar pagamento com parâmetros separados
            $paymentIntent = $this->stripe->paymentIntents->confirm(
                $paymentIntentId,
                ['payment_method' => $paymentMethodId]
            );

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception('Erro ao confirmar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Recuperar um Payment Intent
     */
    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            // Sanitizar dados de entrada
            $paymentIntentId = $this->sanitizePaymentData($paymentIntentId);

            // Validar ID
            if (empty($paymentIntentId) || !is_string($paymentIntentId)) {
                throw new \Exception('ID do Payment Intent inválido');
            }

            // Forçar conversão para string
            $paymentIntentId = $this->forceString($paymentIntentId);

            // Garantir que o ID seja uma string válida
            $paymentIntentId = (string) $paymentIntentId;

            // Validar que é uma string
            if (!is_string($paymentIntentId)) {
                throw new \Exception("ID inválido para Stripe: não é string");
            }

            return $this->stripe->paymentIntents->retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            throw new \Exception('Erro ao recuperar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Criar um Customer no Stripe
     */
    public function createCustomer($user)
    {
        try {
            $customer = $this->stripe->customers->create([
                'email' => (string) ($user->email ?? ''),
                'name' => (string) ($user->name ?? ''),
                'metadata' => [
                    'user_id' => (string) $user->id,
                ],
            ]);

            return $customer;
        } catch (ApiErrorException $e) {
            throw new \Exception('Erro ao criar cliente: ' . $e->getMessage());
        }
    }

    /**
     * Criar Payment Intent para PIX
     */
    public function createPixPaymentIntent(Order $order)
    {
        try {
            $paymentIntentData = [
                'amount' => (int) ($order->total_amount * 100), // Stripe usa centavos
                'currency' => 'brl',
                'payment_method_types' => ['pix'],
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'user_id' => (string) $order->user_id,
                    'game_username' => (string) ($order->game_username ?? ''),
                    'payment_method' => 'pix',
                ],
                'description' => "Pedido #{$order->id} - " . ($order->game_username ?? 'Cliente'),
            ];

            $paymentIntent = $this->stripe->paymentIntents->create($paymentIntentData);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception('Erro ao criar PIX: ' . $e->getMessage());
        }
    }

    /**
     * Confirmar Payment Intent PIX
     */
    public function confirmPixPaymentIntent($paymentIntentId)
    {
        try {
            // Sanitizar dados de entrada
            $paymentIntentId = $this->sanitizePaymentData($paymentIntentId);

            // Validar ID
            if (empty($paymentIntentId) || !is_string($paymentIntentId)) {
                throw new \Exception('ID do Payment Intent inválido');
            }

            // Forçar conversão para string
            $paymentIntentId = $this->forceString($paymentIntentId);

            $confirmData = [
                'id' => (string) $paymentIntentId,
            ];

            // Garantir que todos os elementos sejam strings
            $confirmData = $this->ensureStringArray($confirmData);

            $paymentIntent = $this->stripe->paymentIntents->confirm($confirmData);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception('Erro ao confirmar PIX: ' . $e->getMessage());
        }
    }

    /**
     * Obter dados do PIX (QR Code e código)
     */
    public function getPixData($paymentIntentId)
    {
        try {
            // Sanitizar dados de entrada
            $paymentIntentId = $this->sanitizePaymentData($paymentIntentId);

            $paymentIntent = $this->retrievePaymentIntent($paymentIntentId);

            if (!$paymentIntent || !isset($paymentIntent->next_action)) {
                throw new \Exception('Dados do PIX não disponíveis');
            }

            $pixData = $paymentIntent->next_action->pix_display_qr_code ?? null;

            if (!$pixData) {
                throw new \Exception('QR Code do PIX não disponível');
            }

            return [
                'qr_code' => $pixData->image_url_png ?? null,
                'pix_code' => $pixData->code ?? null,
                'expires_at' => $pixData->expires_at ?? null,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter dados do PIX: ' . $e->getMessage());
        }
    }

    /**
     * Processar webhook do Stripe
     */
    public function processWebhook($payload, $signature, $endpointSecret)
    {
        try {
            // Validar parâmetros
            if (empty($payload) || !is_string($payload)) {
                throw new \Exception('Payload do webhook inválido');
            }

            if (empty($signature) || !is_string($signature)) {
                throw new \Exception('Assinatura do webhook inválida');
            }

            if (empty($endpointSecret) || !is_string($endpointSecret)) {
                throw new \Exception('Secret do webhook inválido');
            }

            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $endpointSecret
            );

            return $event;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao processar webhook: ' . $e->getMessage());
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

        // Se for objeto, tentar converter para string
        if (is_object($data)) {
            $data = (string) $data;
        }

        // Converter para string e aplicar trim
        $data = trim((string) $data);

        // Validar se não está vazio
        if (empty($data)) {
            throw new \Exception('Dados de pagamento inválidos');
        }

        // Garantir que é uma string válida
        if (!is_string($data)) {
            throw new \Exception('Dados de pagamento devem ser uma string válida');
        }

        return $data;
    }

    /**
     * Forçar conversão para string
     */
    private function forceString($data)
    {
        // Se for array, pegar o primeiro elemento
        if (is_array($data)) {
            $data = $data[0] ?? '';
        }

        // Se for objeto, tentar converter para string
        if (is_object($data)) {
            $data = (string) $data;
        }

        // Converter para string
        $data = (string) $data;

        // Aplicar trim
        $data = trim($data);

        // Validar se não está vazio
        if (empty($data)) {
            throw new \Exception('Dados de pagamento inválidos');
        }

        return $data;
    }

    /**
     * Garantir que todos os elementos de um array sejam strings
     */
    private function ensureStringArray($data)
    {
        if (!is_array($data)) {
            throw new \Exception('Dados devem ser um array');
        }

        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->ensureStringArray($value);
            } else {
                $result[$key] = (string) $value;
            }
        }

        return $result;
    }
}
