<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Exception;

class CheckOrderStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the structure of the orders table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando estrutura da tabela orders...');

        try {
            // Verificar colunas da tabela
            $columns = Schema::getColumnListing('orders');
            $this->info('✅ Colunas encontradas na tabela orders:');
            foreach ($columns as $column) {
                $this->line("   - {$column}");
            }

            // Verificar se há pedidos
            $orderCount = Order::count();
            $this->info("📊 Total de pedidos: {$orderCount}");

            if ($orderCount > 0) {
                $order = Order::first();
                $this->info('📋 Exemplo de pedido:');
                $this->line("   - ID: {$order->id}");
                $this->line("   - Status: " . ($order->status ?? 'N/A'));
                $this->line("   - Payment Method: " . ($order->payment_method ?? 'N/A'));
                $this->line("   - Total: R$ " . number_format($order->total_amount ?? 0, 2, ',', '.'));
                $this->line("   - Game Username: " . ($order->game_username ?? 'N/A'));

                // Verificar todos os campos
                $this->info('🔍 Todos os campos do pedido:');
                $attributes = $order->getAttributes();
                foreach ($attributes as $key => $value) {
                    $this->line("   - {$key}: " . (is_null($value) ? 'NULL' : $value));
                }
            } else {
                $this->warn('⚠️ Nenhum pedido encontrado na base de dados');
            }

            return 0;

        } catch (Exception $e) {
            $this->error("❌ Erro ao verificar estrutura:");
            $this->error($e->getMessage());
            return 1;
        }
    }
}
