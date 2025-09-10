<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\HomeController;
use App\Models\Product;

class TestHome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'home:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the home page functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏠 Testando página inicial...');

        try {
            // Testar HomeController
            $controller = new HomeController();
            $this->info('✅ HomeController instanciado com sucesso');

            // Testar busca de produtos
            $featuredProducts = Product::latest()->take(6)->get();
            $this->info("✅ Produtos em destaque encontrados: {$featuredProducts->count()}");

            $goldProducts = Product::where('type', 'gold')->take(4)->get();
            $this->info("✅ Produtos de gold encontrados: {$goldProducts->count()}");

            $popularProducts = Product::inRandomOrder()->take(4)->get();
            $this->info("✅ Produtos populares encontrados: {$popularProducts->count()}");

            // Testar se a view existe
            if (view()->exists('home')) {
                $this->info('✅ View home.blade.php encontrada');
            } else {
                $this->error('❌ View home.blade.php não encontrada');
                return 1;
            }

            // Testar se a rota existe
            $routes = app('router')->getRoutes();
            $homeRoute = $routes->getByName('home');

            if ($homeRoute) {
                $this->info('✅ Rota home encontrada: ' . $homeRoute->uri());
            } else {
                $this->error('❌ Rota home não encontrada');
                return 1;
            }

            $this->info('🎉 Página inicial configurada corretamente!');
            $this->line('');
            $this->line('📋 Resumo:');
            $this->line('   - Controller: ✅');
            $this->line('   - View: ✅');
            $this->line('   - Rota: ✅');
            $this->line('   - Produtos: ✅');
            $this->line('');
            $this->line('🌐 Acesse: http://localhost:8000');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erro ao testar página inicial:');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
