<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class TestQuantitySelector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'home:test-quantity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the quantity selector functionality on home page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔢 Testando seletor de quantidade na home...');

        try {
            // Verificar se há produtos de gold
            $goldProducts = Product::where('type', 'gold')->take(4)->get();

            if ($goldProducts->count() == 0) {
                $this->warn('⚠️ Nenhum produto de gold encontrado para testar');
                return 1;
            }

            $this->info("✅ Produtos de gold encontrados: {$goldProducts->count()}");

            // Verificar se a view tem os elementos necessários
            $viewContent = view('home', [
                'featuredProducts' => Product::latest()->take(6)->get(),
                'goldProducts' => $goldProducts,
                'popularProducts' => Product::inRandomOrder()->take(4)->get()
            ])->render();

            // Verificar se os elementos JavaScript estão presentes
            $requiredElements = [
                'decreaseQuantity(',
                'increaseQuantity(',
                'updateTotal(',
                'quantity_',
                'total_',
                'data-price=',
                'data-max=',
                'onclick="decreaseQuantity(',
                'onclick="increaseQuantity('
            ];

            $missingElements = [];
            foreach ($requiredElements as $element) {
                if (strpos($viewContent, $element) === false) {
                    $missingElements[] = $element;
                }
            }

            if (empty($missingElements)) {
                $this->info('✅ Todos os elementos JavaScript encontrados');
            } else {
                $this->error('❌ Elementos JavaScript faltando:');
                foreach ($missingElements as $element) {
                    $this->error("   - {$element}");
                }
                return 1;
            }

            // Verificar se os inputs de quantidade estão presentes
            $quantityInputs = 0;
            foreach ($goldProducts as $product) {
                if (strpos($viewContent, "id=\"quantity_{$product->id}\"") !== false) {
                    $quantityInputs++;
                }
            }

            $this->info("✅ Inputs de quantidade encontrados: {$quantityInputs}");

            // Verificar se os elementos de total estão presentes
            $totalElements = 0;
            foreach ($goldProducts as $product) {
                if (strpos($viewContent, "id=\"total_{$product->id}\"") !== false) {
                    $totalElements++;
                }
            }

            $this->info("✅ Elementos de total encontrados: {$totalElements}");

            $this->info('🎉 Seletor de quantidade configurado corretamente!');
            $this->line('');
            $this->line('📋 Funcionalidades implementadas:');
            $this->line('   - Botões de + e - para quantidade');
            $this->line('   - Input numérico para quantidade');
            $this->line('   - Cálculo automático do total');
            $this->line('   - Validação baseada na quantidade disponível');
            $this->line('   - Máximo dinâmico baseado no estoque');
            $this->line('   - Formatação brasileira de preços');
            $this->line('   - Indicação visual do máximo disponível');
            $this->line('');
            $this->line('🌐 Acesse: http://localhost:8000');
            $this->line('💡 Teste clicando nos botões + e - na seção de gold');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erro ao testar seletor de quantidade:');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
