<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StripeService;

class TestStripeConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar conexão com o Stripe';

    /**
     * Execute the console command.
     */
        public function handle()
    {
        $this->info('🧪 Testando instalação do Stripe...');

        try {
            // Verificar se o pacote está instalado
            $this->info('📦 Verificando pacote Stripe...');

            if (!class_exists('Stripe\Stripe')) {
                $this->error('❌ Pacote Stripe não está instalado');
                return 1;
            }

            $this->info('✅ Pacote Stripe instalado com sucesso!');

            // Verificar configurações
            $this->info('📋 Verificando configurações...');

            if (!config('services.stripe.key')) {
                $this->warn('⚠️  STRIPE_KEY não está configurada');
                $this->info('   Para configurar, adicione ao .env: STRIPE_KEY=pk_test_your_key');
            } else {
                $this->info('✅ STRIPE_KEY configurada');
            }

            if (!config('services.stripe.secret')) {
                $this->warn('⚠️  STRIPE_SECRET não está configurada');
                $this->info('   Para configurar, adicione ao .env: STRIPE_SECRET=sk_test_your_secret');
            } else {
                $this->info('✅ STRIPE_SECRET configurada');
            }

            if (!config('services.stripe.webhook_secret')) {
                $this->warn('⚠️  STRIPE_WEBHOOK_SECRET não está configurada');
                $this->info('   Para configurar, adicione ao .env: STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret');
            } else {
                $this->info('✅ STRIPE_WEBHOOK_SECRET configurada');
            }

            $this->info('');
            $this->info('🎉 Integração Stripe instalada com sucesso!');
            $this->info('');
            $this->info('📝 Próximos passos:');
            $this->info('   1. Configure as variáveis no arquivo .env');
            $this->info('   2. Execute: php artisan config:clear');
            $this->info('   3. Teste novamente: php artisan stripe:test');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erro: ' . $e->getMessage());
            return 1;
        }
    }
}
