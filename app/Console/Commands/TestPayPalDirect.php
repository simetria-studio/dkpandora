<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PayPalDirectService;
use App\Models\Order;
use Exception;

class TestPayPalDirect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:test-direct {order_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayPal direct service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');

        $this->info("🧪 Testando serviço direto do PayPal para pedido #{$orderId}...");

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

            // Testar serviço direto
            $this->info("🔄 Testando serviço direto do PayPal...");

            $paypalDirectService = app(PayPalDirectService::class);

            // Testar criação de pedido
            $this->info("1. Testando criação de pedido...");
            $paypalOrder = $paypalDirectService->createOrder($order);

            $this->info("✅ Pedido criado com sucesso!");
            $this->line("   - PayPal Order ID: " . ($paypalOrder['id'] ?? 'N/A'));
            $this->line("   - Status: " . ($paypalOrder['status'] ?? 'N/A'));

            // Verificar links
            $links = $paypalOrder['links'] ?? [];
            $this->line("   - Links encontrados: " . count($links));

            foreach ($links as $link) {
                $this->line("     * {$link['rel']}: {$link['href']}");
            }

            // Verificar se tem URL de aprovação
            $approvalUrl = $paypalDirectService->getApprovalUrl($paypalOrder);

            if ($approvalUrl) {
                $this->info("✅ URL de aprovação encontrada: {$approvalUrl}");
            } else {
                $this->warn("⚠️ URL de aprovação não encontrada");
            }

            $this->info("🎉 Serviço direto funcionando perfeitamente!");
            return 0;

        } catch (Exception $e) {
            $this->error("❌ Erro ao testar serviço direto:");
            $this->error($e->getMessage());
            $this->error("Stack trace:");
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
