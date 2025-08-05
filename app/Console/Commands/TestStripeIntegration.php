<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StripeService;
use App\Models\Order;

class TestStripeIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:stripe-integration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar integração do Stripe com validação de dados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testando integração do Stripe...');

        try {
            // Verificar configurações
            if (!config('services.stripe.key') || !config('services.stripe.secret')) {
                $this->error('❌ Configurações do Stripe não encontradas');
                return 1;
            }

            $stripeService = app(StripeService::class);

            // Testar criação de Payment Intent
            $this->info('📋 Testando criação de Payment Intent...');
            
            $order = Order::first();
            if (!$order) {
                $this->error('❌ Nenhum pedido encontrado para teste');
                return 1;
            }

            // Validar dados do pedido antes de enviar para o Stripe
            $this->info("  - Order ID: " . ($order->id ?? 'null'));
            $this->info("  - User ID: " . ($order->user_id ?? 'null'));
            $this->info("  - Game Username: " . ($order->game_username ?? 'null'));
            $this->info("  - Total Amount: " . ($order->total_amount ?? 'null'));

            // Verificar se os dados são válidos
            if (empty($order->id) || empty($order->user_id) || empty($order->total_amount)) {
                $this->error('❌ Dados do pedido inválidos');
                return 1;
            }

            // Verificar valor mínimo do Stripe (R$ 0,50)
            if ($order->total_amount < 0.50) {
                $this->warn('⚠️  Valor muito baixo para teste. Usando valor mínimo...');
                $order->total_amount = 0.50;
            }

            // Testar criação do Payment Intent
            $paymentIntent = $stripeService->createPaymentIntent($order);
            
            $this->info('✅ Payment Intent criado com sucesso!');
            $this->info("  - Payment Intent ID: {$paymentIntent->id}");
            $this->info("  - Amount: {$paymentIntent->amount}");
            $this->info("  - Currency: {$paymentIntent->currency}");
            $this->info("  - Status: {$paymentIntent->status}");

            // Verificar metadados
            if ($paymentIntent->metadata) {
                $this->info('  - Metadata:');
                foreach ($paymentIntent->metadata as $key => $value) {
                    $this->info("    {$key}: {$value}");
                }
            }

            $this->info('');
            $this->info('🎉 Integração do Stripe funcionando corretamente!');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erro na integração: ' . $e->getMessage());
            $this->error('  - Arquivo: ' . $e->getFile());
            $this->error('  - Linha: ' . $e->getLine());
            return 1;
        }
    }
} 