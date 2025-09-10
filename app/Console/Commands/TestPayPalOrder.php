<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\PayPalService;
use Exception;

class TestPayPalOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:test-order {order_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayPal payment for a specific order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');

        $this->info("🧪 Testando pedido #{$orderId} via PayPal...");

        try {
            // Buscar o pedido
            $order = Order::find($orderId);

            if (!$order) {
                $this->error("❌ Pedido #{$orderId} não encontrado");
                return 1;
            }

            $this->info("✅ Pedido encontrado:");
            $this->line("   - ID: {$order->id}");
            $this->line("   - Status: {$order->status}");
            $this->line("   - Payment Method: {$order->payment_method}");
            $this->line("   - Total: R$ " . number_format($order->total_amount, 2, ',', '.'));
            $this->line("   - Game Username: {$order->game_username}");

            // Verificar se pode ser pago
            if ($order->status !== 'pending') {
                $this->warn("⚠️ Pedido não pode ser pago - Status: {$order->status}");
                $this->line("   Status esperado: pending");
            }

            // Testar criação do pedido PayPal
            $this->info("🔄 Testando criação do pedido no PayPal...");

            $paypalService = app(PayPalService::class);
            $paypalOrder = $paypalService->createOrder($order);

            $this->info("✅ Pedido criado no PayPal com sucesso!");

            if ($paypalOrder) {
                $body = $paypalOrder->getBody();
                if ($body) {
                    $this->line("   - PayPal Order ID: " . ($body->id ?? 'N/A'));
                    $this->line("   - Status: " . ($body->status ?? 'N/A'));

                    // Verificar links
                    $links = $body->links ?? [];
                    $this->line("   - Links encontrados: " . count($links));

                    foreach ($links as $link) {
                        $this->line("     * {$link->rel}: {$link->href}");
                    }

                    // Verificar se tem URL de aprovação
                    $approvalUrl = null;
                    foreach ($links as $link) {
                        if ($link->rel === 'approve') {
                            $approvalUrl = $link->href;
                            break;
                        }
                    }

                    if ($approvalUrl) {
                        $this->info("✅ URL de aprovação encontrada: {$approvalUrl}");
                    } else {
                        $this->warn("⚠️ URL de aprovação não encontrada");
                    }
                } else {
                    $this->warn("⚠️ Corpo da resposta do PayPal é null");
                }
            }

            return 0;

        } catch (Exception $e) {
            $this->error("❌ Erro ao testar pedido PayPal:");
            $this->error($e->getMessage());
            $this->error("Stack trace:");
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
