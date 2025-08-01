<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Itens
        Product::create([
            'name' => 'Espada Lendária do Dragão',
            'description' => 'Uma espada poderosa forjada com o metal dos dragões antigos. Aumenta significativamente o ataque e possui habilidades especiais.',
            'price' => 89.90,
            'category' => 'weapon',
            'type' => 'item',
            'rarity' => 'legendary',
            'level_required' => 80,
            'stock' => 10,
            'is_active' => true
        ]);

        Product::create([
            'name' => 'Armadura Épica de Cristal',
            'description' => 'Armadura feita de cristais mágicos que oferece proteção excepcional contra ataques mágicos e físicos.',
            'price' => 75.50,
            'category' => 'armor',
            'type' => 'item',
            'rarity' => 'epic',
            'level_required' => 70,
            'stock' => 15,
            'is_active' => true
        ]);

        Product::create([
            'name' => 'Anel da Sorte',
            'description' => 'Anel mágico que aumenta a chance de encontrar itens raros e melhora as estatísticas de sorte.',
            'price' => 45.00,
            'category' => 'accessory',
            'type' => 'item',
            'rarity' => 'rare',
            'level_required' => 50,
            'stock' => 25,
            'is_active' => true
        ]);

        Product::create([
            'name' => 'Poção de Cura Maior',
            'description' => 'Poção que restaura uma grande quantidade de HP instantaneamente. Essencial para dungeons difíceis.',
            'price' => 12.90,
            'category' => 'consumable',
            'type' => 'item',
            'rarity' => 'common',
            'level_required' => 20,
            'stock' => 100,
            'is_active' => true
        ]);

        Product::create([
            'name' => 'Machado Rúnico',
            'description' => 'Machado com runas antigas gravadas. Causa dano adicional contra monstros demoníacos.',
            'price' => 65.00,
            'category' => 'weapon',
            'type' => 'item',
            'rarity' => 'rare',
            'level_required' => 60,
            'stock' => 8,
            'is_active' => true
        ]);

        Product::create([
            'name' => 'Botas do Vento',
            'description' => 'Botas mágicas que aumentam significativamente a velocidade de movimento e permitem saltos mais altos.',
            'price' => 55.00,
            'category' => 'armor',
            'type' => 'item',
            'rarity' => 'epic',
            'level_required' => 65,
            'stock' => 12,
            'is_active' => true
        ]);

        Product::create([
            'name' => 'Pergaminho de Teleporte',
            'description' => 'Pergaminho que permite teleportar para qualquer cidade do mundo. Muito útil para viagens rápidas.',
            'price' => 8.50,
            'category' => 'consumable',
            'type' => 'item',
            'rarity' => 'common',
            'level_required' => 10,
            'stock' => 50,
            'is_active' => true
        ]);

        Product::create([
            'name' => 'Amuleto da Proteção',
            'description' => 'Amuleto sagrado que oferece proteção contra maldições e aumenta a resistência mágica.',
            'price' => 35.00,
            'category' => 'accessory',
            'type' => 'item',
            'rarity' => 'rare',
            'level_required' => 40,
            'stock' => 20,
            'is_active' => true
        ]);

        // Gold
        Product::create([
            'name' => '100.000 Gold',
            'description' => 'Pacote de 100.000 moedas de ouro para usar no jogo. Entrega instantânea após confirmação do pagamento.',
            'price' => 25.00,
            'category' => 'currency',
            'type' => 'gold',
            'rarity' => 'common',
            'level_required' => 1,
            'stock' => 999,
            'is_active' => true
        ]);

        Product::create([
            'name' => '500.000 Gold',
            'description' => 'Pacote de 500.000 moedas de ouro. Ideal para jogadores que precisam de uma quantidade maior de moedas.',
            'price' => 110.00,
            'category' => 'currency',
            'type' => 'gold',
            'rarity' => 'common',
            'level_required' => 1,
            'stock' => 999,
            'is_active' => true
        ]);

        Product::create([
            'name' => '1.000.000 Gold',
            'description' => 'Pacote de 1.000.000 moedas de ouro. A melhor opção para quem quer economizar e comprar em grande quantidade.',
            'price' => 200.00,
            'category' => 'currency',
            'type' => 'gold',
            'rarity' => 'common',
            'level_required' => 1,
            'stock' => 999,
            'is_active' => true
        ]);

        Product::create([
            'name' => '2.000.000 Gold',
            'description' => 'Pacote de 2.000.000 moedas de ouro. Oferta especial para jogadores que precisam de muito ouro.',
            'price' => 380.00,
            'category' => 'currency',
            'type' => 'gold',
            'rarity' => 'common',
            'level_required' => 1,
            'stock' => 999,
            'is_active' => true
        ]);
    }
} 