<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StripeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se as configurações do Stripe estão definidas
        if (!config('services.stripe.key') || !config('services.stripe.secret')) {
            return redirect()->back()->with('error', 'Configuração de pagamento não está disponível. Entre em contato com o suporte.');
        }

        return $next($request);
    }
}
