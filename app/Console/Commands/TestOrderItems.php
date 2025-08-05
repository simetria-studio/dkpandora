<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItem;

class TestOrderItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:order-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar estrutura dos OrderItems';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testando estrutura dos OrderItems...');

        $orderItems = OrderItem::all();

        if ($orderItems->isEmpty()) {
            $this->warn('⚠️  Nenhum OrderItem encontrado');
            return 0;
        }

        $this->info('📊 Encontrados ' . $orderItems->count() . ' OrderItems:');
        $this->info('');

        foreach ($orderItems as $item) {
            $this->info("ID: {$item->id}");
            $this->info("  - Product ID: " . ($item->product_id ?? 'null'));
            $this->info("  - Product Name: " . ($item->product_name ?? 'null'));
            $this->info("  - Quantity: {$item->quantity}");
            $this->info("  - Price: {$item->price}");

            if ($item->product) {
                $this->info("  - Product exists: ✅");
                $this->info("  - Product name: {$item->product->name}");
            } else {
                $this->info("  - Product exists: ❌ (null)");
            }

            $this->info('');
        }

        return 0;
    }
}
