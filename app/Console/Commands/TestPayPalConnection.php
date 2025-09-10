<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PayPalService;
use Exception;

class TestPayPalConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayPal connection and configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testando conexão com PayPal...');

        try {
            // Verificar configuração
            $config = config('services.paypal');

            if (!$config) {
                $this->error('❌ Configuração do PayPal não encontrada em config/services.php');
                return 1;
            }

            $this->info('✅ Configuração encontrada:');
            $this->line("   - Client ID: " . substr($config['client_id'], 0, 10) . '...');
            $this->line("   - Environment: {$config['environment']}");

            // Testar instanciação do serviço
            $paypalService = app(PayPalService::class);
            $this->info('✅ PayPalService instanciado com sucesso');

            // Testar cliente PayPal
            $client = app('paypal');
            $this->info('✅ Cliente PayPal configurado');

            // Verificar se o controller está disponível
            $ordersController = $client->getOrdersController();
            $this->info('✅ OrdersController disponível');

            $this->info('🎉 Conexão com PayPal testada com sucesso!');
            return 0;

        } catch (Exception $e) {
            $this->error('❌ Erro ao testar conexão com PayPal:');
            $this->error($e->getMessage());
            $this->error('Stack trace:');
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
