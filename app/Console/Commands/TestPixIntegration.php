<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StripeService;
use App\Models\Order;

class TestPixIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pix-integration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar integração PIX';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testando integração PIX...');

        try {
            // Verificar configurações
            if (!config('services.stripe.key') || !config('services.stripe.secret')) {
                $this->error('❌ Configurações do Stripe não encontradas');
                return 1;
            }

            $stripeService = app(StripeService::class);

            // Testar criação de Payment Intent PIX
            $this->info('📋 Testando criação de Payment Intent PIX...');
            
            $order = Order::first();
            if (!$order) {
                $this->error('❌ Nenhum pedido encontrado para teste');
                return 1;
            }

            // Verificar valor mínimo
            if ($order->total_amount < 0.50) {
                $this->warn('⚠️  Valor muito baixo para teste. Usando valor mínimo...');
                $order->total_amount = 0.50;
            }

            // Testar criação do Payment Intent PIX
            $paymentIntent = $stripeService->createPixPaymentIntent($order);
            
            $this->info('✅ Payment Intent PIX criado com sucesso!');
            $this->info("  - Payment Intent ID: {$paymentIntent->id}");
            $this->info("  - Amount: {$paymentIntent->amount}");
            $this->info("  - Currency: {$paymentIntent->currency}");
            $this->info("  - Status: {$paymentIntent->status}");

            // Testar obtenção dos dados do PIX
            $this->info('📱 Testando obtenção dos dados do PIX...');
            
            try {
                $pixData = $stripeService->getPixData($paymentIntent->id);
                
                $this->info('✅ Dados do PIX obtidos com sucesso!');
                $this->info("  - QR Code: " . ($pixData['qr_code'] ? 'Disponível' : 'Não disponível'));
                $this->info("  - Código PIX: " . ($pixData['pix_code'] ? 'Disponível' : 'Não disponível'));
                $this->info("  - Expira em: " . ($pixData['expires_at'] ? date('d/m/Y H:i', $pixData['expires_at']) : 'Não definido'));
                
            } catch (\Exception $e) {
                $this->warn('⚠️  Dados do PIX não disponíveis ainda: ' . $e->getMessage());
                $this->info('   Isso é normal, os dados podem levar alguns segundos para ficarem disponíveis.');
            }

            $this->info('');
            $this->info('🎉 Integração PIX funcionando corretamente!');
            $this->info('');
            $this->info('📝 Próximos passos:');
            $this->info('   1. Acesse a URL: /payments/' . $order->id . '/pix');
            $this->info('   2. Escaneie o QR Code ou copie o código PIX');
            $this->info('   3. Faça o pagamento no app do seu banco');
            $this->info('   4. O status será atualizado automaticamente');
            
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erro na integração PIX: ' . $e->getMessage());
            $this->error('  - Arquivo: ' . $e->getFile());
            $this->error('  - Linha: ' . $e->getLine());
            return 1;
        }
    }
} 