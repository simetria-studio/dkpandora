<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class TestOrderView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:order-view {order_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar view de pedidos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        if (!$orderId) {
            $order = Order::with('orderItems.product')->first();
        } else {
            $order = Order::with('orderItems.product')->find($orderId);
        }

        if (!$order) {
            $this->error('❌ Nenhum pedido encontrado');
            return 1;
        }

        $this->info("🧪 Testando view do pedido #{$order->id}");
        $this->info('');

        foreach ($order->orderItems as $item) {
            $this->info("Item ID: {$item->id}");
            $this->info("  - Product ID: " . ($item->product_id ?? 'null'));
            $this->info("  - Product Name: " . ($item->product_name ?? 'null'));
            
            // Testar acessos que podem causar erro
            try {
                if ($item->product) {
                    $this->info("  - Product exists: ✅");
                    $this->info("  - Product name: " . ($item->product->name ?? 'null'));
                    $this->info("  - Product image: " . ($item->product->image ?? 'null'));
                    $this->info("  - Product description: " . ($item->product->description ?? 'null'));
                    $this->info("  - Product rarity: " . ($item->product->rarity ?? 'null'));
                } else {
                    $this->info("  - Product exists: ❌ (null)");
                }
            } catch (\Exception $e) {
                $this->error("  - Erro ao acessar produto: " . $e->getMessage());
            }
            
            $this->info('');
        }

        $this->info('✅ Teste concluído!');
        return 0;
    }
} 