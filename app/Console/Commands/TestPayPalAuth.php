<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;

class TestPayPalAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:test-auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayPal OAuth authentication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Testando autenticação OAuth do PayPal...');

        try {
            // Obter o cliente PayPal
            $client = app('paypal');
            $this->info('✅ Cliente PayPal obtido');

            // Verificar se tem o método de autenticação
            $this->info('1. Verificando métodos de autenticação...');

            $reflection = new \ReflectionClass($client);
            $methods = $reflection->getMethods();

            $authMethods = array_filter($methods, function($method) {
                return strpos($method->getName(), 'Auth') !== false ||
                       strpos($method->getName(), 'auth') !== false;
            });

            if (empty($authMethods)) {
                $this->warn('⚠️ Nenhum método de autenticação encontrado');
            } else {
                $this->info('✅ Métodos de autenticação encontrados:');
                foreach ($authMethods as $method) {
                    $this->line("   - " . $method->getName());
                }
            }

            // Tentar obter o OrdersController
            $this->info('2. Tentando obter OrdersController...');

            try {
                $ordersController = $client->getOrdersController();
                $this->info('✅ OrdersController obtido');

                // Verificar se consegue fazer uma chamada simples
                $this->info('3. Testando chamada simples para a API...');

                // Tentar criar um pedido de teste mínimo
                $testOptions = [
                    'body' => [
                        'intent' => 'CAPTURE',
                        'purchase_units' => [[
                            'amount' => [
                                'currency_code' => 'BRL',
                                'value' => '10.00'
                            ]
                        ]]
                    ]
                ];

                $this->info('4. Tentando criar pedido de teste...');
                $response = $ordersController->createOrder($testOptions);

                $this->info('🎉 Autenticação funcionando! Pedido criado com sucesso');
                $this->line("   - PayPal Order ID: " . ($response->getBody()->id ?? 'N/A'));
                $this->line("   - Status: " . ($response->getBody()->status ?? 'N/A'));

                return 0;

            } catch (Exception $e) {
                $this->error('❌ Erro ao criar pedido de teste:');
                $this->error($e->getMessage());

                // Verificar se é erro de autenticação
                if (strpos($e->getMessage(), 'OAuth') !== false ||
                    strpos($e->getMessage(), 'authentication') !== false ||
                    strpos($e->getMessage(), 'authorized') !== false) {
                    $this->error('🔐 Problema de autenticação OAuth detectado');
                    $this->line('   Verifique suas credenciais no .env');
                    $this->line('   Certifique-se de que o ambiente está correto (sandbox/production)');
                }

                return 1;
            }

        } catch (Exception $e) {
            $this->error("❌ Erro geral:");
            $this->error($e->getMessage());
            $this->error("Stack trace:");
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
