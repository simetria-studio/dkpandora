<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PayPalDirectService
{
    protected $clientId;
    protected $clientSecret;
    protected $environment;
    protected $baseUrl;
    protected $accessToken;

    public function __construct()
    {
        $config = config('services.paypal');
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->environment = $config['environment'];

        // Definir URL base baseada no ambiente
        $this->baseUrl = $this->environment === 'production'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Obter token de acesso OAuth
     */
    protected function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->withOptions([
                    'verify' => false, // Desabilitar verificação SSL para desenvolvimento
                    'timeout' => 30
                ])
                ->asForm()
                ->post($this->baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->accessToken = $data['access_token'];
                Log::info('PayPal Direct: Token OAuth obtido com sucesso');
                return $this->accessToken;
            } else {
                Log::error('PayPal Direct: Erro ao obter token OAuth', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('Falha ao obter token OAuth: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('PayPal Direct: Exceção ao obter token OAuth', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Criar pedido no PayPal
     */
    public function createOrder(Order $order)
    {
        try {
            $token = $this->getAccessToken();

            $payload = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => (string) $order->id,
                        'description' => "Pedido #{$order->id} - " . ($order->game_username ?? 'Cliente'),
                        'custom_id' => (string) $order->id,
                        'amount' => [
                            'currency_code' => 'BRL',
                            'value' => number_format($order->total_amount, 2, '.', '')
                        ]
                    ]
                ],
                'application_context' => [
                    'return_url' => route('paypal.success', $order),
                    'cancel_url' => route('paypal.cancel', $order),
                    'brand_name' => config('app.name'),
                    'landing_page' => 'LOGIN',
                    'user_action' => 'PAY_NOW'
                ]
            ];

            Log::info('PayPal Direct: Criando pedido', [
                'order_id' => $order->id,
                'payload' => $payload
            ]);

            $response = Http::withToken($token)
                ->withOptions([
                    'verify' => false, // Desabilitar verificação SSL para desenvolvimento
                    'timeout' => 30
                ])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->baseUrl . '/v2/checkout/orders', $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('PayPal Direct: Pedido criado com sucesso', [
                    'order_id' => $order->id,
                    'paypal_order_id' => $data['id'] ?? 'N/A'
                ]);
                return $data;
            } else {
                Log::error('PayPal Direct: Erro ao criar pedido', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('Falha ao criar pedido PayPal: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('PayPal Direct: Exceção ao criar pedido', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Capturar pedido
     */
    public function captureOrder($orderId)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->withOptions([
                    'verify' => false, // Desabilitar verificação SSL para desenvolvimento
                    'timeout' => 30
                ])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->baseUrl . "/v2/checkout/orders/{$orderId}/capture");

            if ($response->successful()) {
                $data = $response->json();
                Log::info('PayPal Direct: Pedido capturado com sucesso', [
                    'paypal_order_id' => $orderId,
                    'capture_id' => $data['purchase_units'][0]['payments']['captures'][0]['id'] ?? 'N/A'
                ]);
                return $data;
            } else {
                Log::error('PayPal Direct: Erro ao capturar pedido', [
                    'paypal_order_id' => $orderId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('Falha ao capturar pedido PayPal: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('PayPal Direct: Exceção ao capturar pedido', [
                'paypal_order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obter detalhes do pedido
     */
    public function getOrder($orderId)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->withOptions([
                    'verify' => false, // Desabilitar verificação SSL para desenvolvimento
                    'timeout' => 30
                ])
                ->withHeaders([
                    'Accept' => 'application/json'
                ])
                ->get($this->baseUrl . "/v2/checkout/orders/{$orderId}");

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('PayPal Direct: Erro ao obter pedido', [
                    'paypal_order_id' => $orderId,
                    'status' => $response->status()
                ]);
                throw new \Exception('Falha ao obter pedido PayPal: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('PayPal Direct: Exceção ao obter pedido', [
                'paypal_order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Extrair URL de aprovação do pedido
     */
    public function getApprovalUrl($paypalOrder)
    {
        if (!is_array($paypalOrder) || !isset($paypalOrder['links'])) {
            return null;
        }

        foreach ($paypalOrder['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return $link['href'];
            }
        }

        return null;
    }
}
