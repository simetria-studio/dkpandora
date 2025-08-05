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
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'game_username' => $order->game_username,
                ],
                'description' => "Pedido #{$order->id} - {$order->game_username}",
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
            $paymentIntent = $this->stripe->paymentIntents->confirm([
                'id' => $paymentIntentId,
                'payment_method' => $paymentMethodId,
            ]);

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
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            return $customer;
        } catch (ApiErrorException $e) {
            throw new \Exception('Erro ao criar cliente: ' . $e->getMessage());
        }
    }

    /**
     * Processar webhook do Stripe
     */
    public function processWebhook($payload, $signature, $endpointSecret)
    {
        try {
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
}
