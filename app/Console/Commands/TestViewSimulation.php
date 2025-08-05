<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class TestViewSimulation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:view-simulation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simular exatamente o que acontece na view';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Simulando view de pedidos...');

        $order = Order::with('orderItems.product')->first();

        if (!$order) {
            $this->error('❌ Nenhum pedido encontrado');
            return 1;
        }

        $this->info("Testando pedido #{$order->id}");
        $this->info('');

        foreach ($order->orderItems as $item) {
            $this->info("Testando item #{$item->id}");

            // Simular exatamente o que acontece na view
            try {
                // Teste 1: Verificar se product existe
                if ($item->product) {
                    $this->info("  ✅ Product existe");

                    // Teste 2: Verificar se product->image existe
                    if ($item->product->image) {
                        $this->info("  ✅ Product->image existe: {$item->product->image}");
                    } else {
                        $this->info("  ⚠️  Product->image é null");
                    }

                    // Teste 3: Verificar se product->name existe
                    if ($item->product->name) {
                        $this->info("  ✅ Product->name existe: {$item->product->name}");
                    } else {
                        $this->info("  ⚠️  Product->name é null");
                    }

                    // Teste 4: Verificar se product->description existe
                    if ($item->product->description) {
                        $this->info("  ✅ Product->description existe");
                    } else {
                        $this->info("  ⚠️  Product->description é null");
                    }

                    // Teste 5: Verificar se product->rarity existe
                    if ($item->product->rarity) {
                        $this->info("  ✅ Product->rarity existe: {$item->product->rarity}");
                    } else {
                        $this->info("  ⚠️  Product->rarity é null");
                    }

                } else {
                    $this->info("  ❌ Product é null");
                    $this->info("  - Product name: " . ($item->product_name ?? 'null'));
                    $this->info("  - Product description: " . ($item->product_description ?? 'null'));
                }

            } catch (\Exception $e) {
                $this->error("  ❌ Erro: " . $e->getMessage());
                $this->error("  - Linha: " . $e->getLine());
                $this->error("  - Arquivo: " . $e->getFile());
            }

            $this->info('');
        }

        $this->info('✅ Simulação concluída!');
        return 0;
    }
}
