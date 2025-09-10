<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;

class TestPayPalStepByStep extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:test-step-by-step';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayPal configuration step by step';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testando configuração do PayPal passo a passo...');

        try {
            $config = config('services.paypal');

            $this->info('1. Configuração do .env:');
            $this->line("   - Client ID: " . ($config['client_id'] ? substr($config['client_id'], 0, 10) . '...' : 'NÃO CONFIGURADO'));
            $this->line("   - Client Secret: " . ($config['client_secret'] ? 'CONFIGURADO' : 'NÃO CONFIGURADO'));
            $this->line("   - Environment: " . ($config['environment'] ?? 'NÃO CONFIGURADO'));

            // Mapear ambiente
            $this->info('2. Mapeando ambiente...');
            $environment = strtolower($config['environment']) === 'production'
                ? Environment::PRODUCTION
                : Environment::SANDBOX;

            $this->line("   - Ambiente mapeado: {$environment}");

            // Criar credenciais
            $this->info('3. Criando credenciais de autenticação...');

            $authCredentials = ClientCredentialsAuthCredentialsBuilder::init(
                $config['client_id'],
                $config['client_secret']
            );

            $this->info('✅ Credenciais criadas');

            // Criar builder
            $this->info('4. Criando builder do cliente...');

            $clientBuilder = PaypalServerSdkClientBuilder::init()
                ->environment($environment)
                ->clientCredentialsAuthCredentials($authCredentials)
                ->timeout(30)
                ->enableRetries(true)
                ->numberOfRetries(3);

            $this->info('✅ Builder criado');

            // Construir cliente
            $this->info('5. Construindo cliente...');

            $client = $clientBuilder->build();

            $this->info('✅ Cliente construído');
            $this->line("   - Classe: " . get_class($client));

            // Verificar configuração
            $this->info('6. Verificando configuração interna...');

            $clientConfig = $client->getConfiguration();
            $this->line("   - Environment: " . ($clientConfig['environment'] ?? 'N/A'));
            $this->line("   - OAuth Client ID: " . ($clientConfig['oAuthClientId'] ?? 'N/A'));
            $this->line("   - OAuth Client Secret: " . ($clientConfig['oAuthClientSecret'] ? 'CONFIGURADO' : 'NÃO CONFIGURADO'));

            // Testar OrdersController
            $this->info('7. Testando OrdersController...');

            $ordersController = $client->getOrdersController();
            $this->info('✅ OrdersController obtido');

            // Testar criação de pedido
            $this->info('8. Testando criação de pedido...');

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

            $response = $ordersController->createOrder($testOptions);

            $this->info('🎉 Pedido criado com sucesso!');
            $this->line("   - PayPal Order ID: " . ($response->getBody()->id ?? 'N/A'));
            $this->line("   - Status: " . ($response->getBody()->status ?? 'N/A'));

            return 0;

        } catch (Exception $e) {
            $this->error("❌ Erro durante o teste:");
            $this->error($e->getMessage());
            $this->error("Stack trace:");
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
