<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PaypalServerSdkLib\PaypalServerSdkClient;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;

class PayPalServiceProvider extends ServiceProvider
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
                ->timeout(60) // Aumentar timeout para 60 segundos
                ->enableRetries(true) // Habilitar retry
                ->numberOfRetries(3); // 3 tentativas

            return $clientBuilder->build();
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
