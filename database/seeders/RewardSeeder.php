<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reward;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'Primeira Compra',
                'description' => 'Desconto especial para novos clientes',
                'required_amount' => 50.00,
                'reward_type' => 'discount',
                'reward_data' => [
                    'discount_percentage' => 10,
                    'valid_days' => 30
                ],
                'is_active' => true,
                'max_redemptions' => null,
            ],
            [
                'name' => 'Cliente Fiel',
                'description' => 'Desconto para clientes que gastaram mais de R$ 200',
                'required_amount' => 200.00,
                'reward_type' => 'discount',
                'reward_data' => [
                    'discount_percentage' => 15,
                    'valid_days' => 60
                ],
                'is_active' => true,
                'max_redemptions' => null,
            ],
            [
                'name' => 'VIP Bronze',
                'description' => 'Produto gratuito para gastos acima de R$ 500',
                'required_amount' => 500.00,
                'reward_type' => 'product',
                'reward_data' => [
                    'product_name' => 'Pacote de Moedas VIP',
                    'product_id' => 1,
                    'quantity' => 1
                ],
                'is_active' => true,
                'max_redemptions' => 100,
            ],
            [
                'name' => 'VIP Prata',
                'description' => 'Bônus especial para gastos acima de R$ 1000',
                'required_amount' => 1000.00,
                'reward_type' => 'bonus',
                'reward_data' => [
                    'bonus_type' => 'XP Bonus',
                    'bonus_value' => 5000,
                    'description' => 'Bônus de 5000 XP para acelerar o progresso'
                ],
                'is_active' => true,
                'max_redemptions' => 50,
            ],
            [
                'name' => 'VIP Ouro',
                'description' => 'Cashback para gastos acima de R$ 2000',
                'required_amount' => 2000.00,
                'reward_type' => 'cashback',
                'reward_data' => [
                    'cashback_percentage' => 5,
                ],
                'is_active' => true,
                'max_redemptions' => 25,
            ],
            [
                'name' => 'VIP Diamante',
                'description' => 'Recompensa máxima para gastos acima de R$ 5000',
                'required_amount' => 5000.00,
                'reward_type' => 'bonus',
                'reward_data' => [
                    'bonus_type' => 'Título Exclusivo',
                    'bonus_value' => 1,
                    'description' => 'Título exclusivo "Diamante" + 10000 moedas'
                ],
                'is_active' => true,
                'max_redemptions' => 10,
            ],
        ];

        foreach ($rewards as $rewardData) {
            Reward::create($rewardData);
        }
    }
}
