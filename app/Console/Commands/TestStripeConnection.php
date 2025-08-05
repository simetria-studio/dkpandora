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
        $this->info('🧪 Testando conexão com o Stripe...');

        try {
            // Verificar configurações
            $this->info('📋 Verificando configurações...');

            if (!config('services.stripe.key')) {
                $this->error('❌ STRIPE_KEY não está configurada');
                return 1;
            }

            if (!config('services.stripe.secret')) {
                $this->error('❌ STRIPE_SECRET não está configurada');
                return 1;
            }

            $this->info('✅ Configurações encontradas');

            // Testar conexão
            $this->info('🔗 Testando conexão...');

            $stripeService = app(StripeService::class);
            $stripe = app('stripe');

            // Tentar recuperar uma lista de customers (teste simples)
            $customers = $stripe->customers->list(['limit' => 1]);

            $this->info('✅ Conexão com Stripe estabelecida com sucesso!');

            // Mostrar informações da conta
            $this->info('📊 Informações da conta:');
            $this->info('   - Modo: ' . (config('services.stripe.key') === 'pk_test_' ? 'Teste' : 'Produção'));
            $this->info('   - Total de customers: ' . $customers->total_count);

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erro ao conectar com Stripe: ' . $e->getMessage());
            return 1;
        }
    }
}
