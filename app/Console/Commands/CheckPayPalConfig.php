<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;

class CheckPayPalConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:check-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check PayPal configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando configuração do PayPal...');

        try {
            $config = config('services.paypal');

            if (!$config) {
                $this->error('❌ Configuração do PayPal não encontrada');
                return 1;
            }

            $this->info('✅ Configuração encontrada:');
            $this->line("   - Client ID: " . ($config['client_id'] ? substr($config['client_id'], 0, 10) . '...' : 'NÃO CONFIGURADO'));
            $this->line("   - Client Secret: " . ($config['client_secret'] ? 'CONFIGURADO' : 'NÃO CONFIGURADO'));
            $this->line("   - Environment: " . ($config['environment'] ?? 'NÃO CONFIGURADO'));
            $this->line("   - Webhook Secret: " . ($config['webhook_secret'] ? 'CONFIGURADO' : 'NÃO CONFIGURADO'));

            // Verificar se as credenciais estão vazias
            if (empty($config['client_id']) || empty($config['client_secret'])) {
                $this->error('❌ Credenciais do PayPal não configuradas corretamente');
                $this->line('   Verifique seu arquivo .env');
                return 1;
            }

            $this->info('✅ Credenciais configuradas corretamente');
            return 0;

        } catch (Exception $e) {
            $this->error("❌ Erro ao verificar configuração:");
            $this->error($e->getMessage());
            return 1;
        }
    }
}
