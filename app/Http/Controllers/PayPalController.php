<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PayPalService;
use App\Services\PayPalDirectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    protected $paypalService;
    protected $paypalDirectService;

    public function __construct(PayPalService $paypalService, PayPalDirectService $paypalDirectService)
    {
        $this->paypalService = $paypalService;
        $this->paypalDirectService = $paypalDirectService;
    }

    /**
     * Iniciar pagamento PayPal
     */
    public function processPayment(Order $order)
    {
        try {
            Log::info('PayPal: Iniciando processamento de pagamento', [
                'order_id' => $order->id,
                'order_status' => $order->status,
                'payment_method' => $order->payment_method,
                'total_amount' => $order->total_amount,
                'user_id' => $order->user_id
            ]);

            // Verificar se o pedido pode ser pago
            if ($order->status !== 'pending') {
                Log::warning('PayPal: Pedido não pode ser pago - status incorreto', [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'expected_status' => 'pending'
                ]);

                return redirect()->route('orders.show', $order)
                    ->with('error', 'Este pedido não pode ser pago via PayPal. Status atual: ' . $order->status);
            }

            Log::info('PayPal: Validações passaram, criando pedido no PayPal', [
                'order_id' => $order->id
            ]);

            // Usar apenas o serviço direto (mais confiável)
            $paypalOrder = $this->paypalDirectService->createOrder($order);
            Log::info('PayPal: Pedido criado com sucesso via serviço direto', [
                'order_id' => $order->id,
                'paypal_order_id' => $paypalOrder['id'] ?? 'N/A',
                'paypal_order_status' => $paypalOrder['status'] ?? 'N/A'
            ]);

            // Redirecionar para o PayPal
            $approvalUrl = $this->getApprovalUrl($paypalOrder);

            if ($approvalUrl) {
                Log::info('PayPal: URL de aprovação encontrada, redirecionando', [
                    'order_id' => $order->id,
                    'approval_url' => $approvalUrl
                ]);

                return redirect($approvalUrl);
            } else {
                Log::error('PayPal: URL de aprovação não encontrada', [
                    'order_id' => $order->id,
                    'paypal_response' => $paypalOrder
                ]);

                throw new \Exception('URL de aprovação não encontrada no pedido PayPal');
            }
        } catch (\Exception $e) {
            Log::error('PayPal: Erro ao processar pagamento', [
                'order_id' => $order->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'order_data' => [
                    'status' => $order->status,
                    'payment_method' => $order->payment_method,
                    'total_amount' => $order->total_amount
                ]
            ]);

            return redirect()->route('orders.show', $order)
                ->with('error', 'Erro ao processar pagamento PayPal: ' . $e->getMessage());
        }
    }

    /**
     * Sucesso do pagamento PayPal
     */
    public function success(Request $request, Order $order)
    {
        try {
            $token = $request->get('token');

            if (!$token) {
                throw new \Exception('Token de pagamento não fornecido');
            }

            Log::info('PayPal: Processando sucesso do pagamento', [
                'order_id' => $order->id,
                'token' => $token
            ]);

            // Verificar o status do pedido PayPal primeiro
            $paypalOrder = $this->paypalDirectService->getOrder($token);
            $status = $paypalOrder['status'] ?? 'UNKNOWN';

            Log::info('PayPal: Status do pedido PayPal', [
                'order_id' => $order->id,
                'paypal_status' => $status
            ]);

            if ($status === 'APPROVED') {
                // Capturar o pagamento se estiver aprovado
                $captureResult = $this->paypalDirectService->captureOrder($token);
                Log::info('PayPal: Pagamento capturado com sucesso', [
                    'order_id' => $order->id,
                    'capture_id' => $captureResult['purchase_units'][0]['payments']['captures'][0]['id'] ?? 'N/A'
                ]);

                // Atualizar status do pedido
                $order->update([
                    'status' => 'completed',
                    'payment_method' => 'paypal',
                    'payment_id' => $token
                ]);

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Pagamento processado com sucesso!');
            } else {
                // Se não estiver aprovado, marcar como pendente e aguardar webhook
                $order->update([
                    'status' => 'pending',
                    'payment_method' => 'paypal',
                    'payment_id' => $token
                ]);

                return redirect()->route('orders.show', $order)
                    ->with('info', 'Pagamento aprovado! Aguardando confirmação...');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao processar sucesso do pagamento PayPal: ' . $e->getMessage());
            return redirect()->route('orders.show', $order)
                ->with('error', 'Erro ao processar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Cancelamento do pagamento PayPal
     */
    public function cancel(Order $order)
    {
        return redirect()->route('orders.show', $order)
            ->with('info', 'Pagamento cancelado pelo usuário.');
    }

    /**
     * Webhook do PayPal
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->all();

            // Log do webhook para debug
            Log::info('Webhook PayPal recebido', $payload);

            // Processar webhook
            $result = $this->paypalService->processWebhook($payload);

            Log::info('Webhook PayPal processado', $result);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook PayPal: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Extrair URL de aprovação do pedido PayPal
     */
    protected function getApprovalUrl($paypalOrder)
    {
        try {
            Log::info('PayPal: Extraindo URL de aprovação', [
                'paypal_order' => $paypalOrder ? 'disponível' : 'não disponível'
            ]);

            if (!$paypalOrder || !is_array($paypalOrder)) {
                Log::error('PayPal: Pedido PayPal é null ou não é array');
                return null;
            }

            $links = $paypalOrder['links'] ?? [];
            Log::info('PayPal: Links encontrados', [
                'links_count' => count($links),
                'links' => $links
            ]);

            foreach ($links as $link) {
                Log::info('PayPal: Verificando link', [
                    'rel' => $link['rel'] ?? 'N/A',
                    'href' => $link['href'] ?? 'N/A'
                ]);

                if ($link['rel'] === 'approve') {
                    Log::info('PayPal: URL de aprovação encontrada', [
                        'href' => $link['href']
                    ]);
                    return $link['href'];
                }
            }

            Log::warning('PayPal: Nenhuma URL de aprovação encontrada nos links');
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal: Erro ao extrair URL de aprovação', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}
