<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CheckGoldQuantities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gold:check-quantities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check available gold quantities for products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🪙 Verificando quantidades de gold disponíveis...');

        $goldProducts = Product::where('type', 'gold')->get(['id', 'name', 'stock', 'price']);

        if ($goldProducts->count() == 0) {
            $this->warn('⚠️ Nenhum produto de gold encontrado');
            return 1;
        }

        $this->line('');
        $this->line('📊 Produtos de Gold Disponíveis:');
        $this->line('================================');

        foreach ($goldProducts as $product) {
            $stock = $product->stock ?? 0;
            $formattedStock = number_format($stock, 0, ',', '.');
            $formattedPrice = number_format($product->price, 2, ',', '.');

            $this->line("ID: {$product->id}");
            $this->line("Nome: {$product->name}");
            $this->line("Gold Disponível: {$formattedStock}");
            $this->line("Preço: R$ {$formattedPrice}");
            $this->line("Máximo que pode ser comprado: {$formattedStock}");
            $this->line('--------------------------------');
        }

        $this->line('');
        $this->info('✅ Verificação concluída!');
        $this->line('💡 Agora o seletor de quantidade respeitará esses limites');

        return 0;
    }
}
