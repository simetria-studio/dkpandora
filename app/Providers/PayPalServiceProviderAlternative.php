<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use PaypalServerSdkLib\PaypalServerSdkClient;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;

class PayPalServiceProviderAlternative extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('paypal', function ($app) {
            $config = config('services.paypal');

            // Mapear ambiente para as constantes corretas do SDK
            $environment = strtolower($config['environment']) === 'production'
                ? Environment::PRODUCTION
                : Environment::SANDBOX;

            $authCredentials = ClientCredentialsAuthCredentialsBuilder::init(
                $config['client_id'],
                $config['client_secret']
            );

            $clientBuilder = PaypalServerSdkClientBuilder::init()
                ->environment($environment)
                ->clientCredentialsAuthCredentials($authCredentials)
                ->timeout(30)
                ->enableRetries(true)
                ->numberOfRetries(3);

            $client = $clientBuilder->build();

            // Contornar o bug de proxy usando reflection para limpar a configuração problemática
            try {
                $reflection = new \ReflectionClass($client);
                $configProperty = $reflection->getProperty('config');
                $configProperty->setAccessible(true);

                $currentConfig = $configProperty->getValue($client);

                // Remover configuração de proxy problemática
                if (isset($currentConfig['proxyConfiguration'])) {
                    unset($currentConfig['proxyConfiguration']);
                    $configProperty->setValue($client, $currentConfig);
                }

            } catch (\Exception $e) {
                // Se falhar, continuar com o cliente original
                Log::warning('Não foi possível contornar bug de proxy: ' . $e->getMessage());
            }

            return $client;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
