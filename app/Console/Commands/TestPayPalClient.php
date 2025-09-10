<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;

class TestPayPalClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:test-client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayPal client instantiation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testando instanciação do cliente PayPal...');

        try {
            // Testar se o serviço está registrado
            $this->info('1. Verificando se o serviço PayPal está registrado...');

            if (!app()->bound('paypal')) {
                $this->error('❌ Serviço PayPal não está registrado');
                return 1;
            }

            $this->info('✅ Serviço PayPal registrado');

            // Testar instanciação
            $this->info('2. Tentando instanciar o cliente PayPal...');

            $client = app('paypal');
            $this->info('✅ Cliente PayPal instanciado');
            $this->line("   - Classe: " . get_class($client));

            // Testar se tem o método getOrdersController
            $this->info('3. Verificando se o cliente tem o método getOrdersController...');

            if (!method_exists($client, 'getOrdersController')) {
                $this->error('❌ Cliente não tem o método getOrdersController');
                return 1;
            }

            $this->info('✅ Método getOrdersController disponível');

            // Testar se consegue obter o controller
            $this->info('4. Tentando obter o OrdersController...');

            $ordersController = $client->getOrdersController();
            $this->info('✅ OrdersController obtido');
            $this->line("   - Classe: " . get_class($ordersController));

            $this->info('🎉 Cliente PayPal funcionando perfeitamente!');
            return 0;

        } catch (Exception $e) {
            $this->error("❌ Erro ao testar cliente PayPal:");
            $this->error($e->getMessage());
            $this->error("Stack trace:");
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
