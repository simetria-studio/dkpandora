<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class FeaturedProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $featuredProducts = [
            [
                'name' => 'Espada Lendária +15',
                'description' => 'Uma espada forjada pelos antigos mestres ferreiros. Possui poderes místicos que aumentam significativamente o poder de ataque do usuário.',
                'price' => 89.90,
                'category' => 'weapons',
                'stock' => 5,
                'level_requirement' => 80,
                'rarity' => 'legendary',
                'features' => ['+1500 Ataque', '+25% Crítico', 'Chance de Atordoar', 'Resistência ao Fogo'],
                'is_featured' => true,
                'is_active' => true,
                'type' => 'item'
            ],
            [
                'name' => 'Armadura Épica do Dragão',
                'description' => 'Armadura forjada com escamas de dragão. Oferece proteção excepcional contra ataques físicos e mágicos.',
                'price' => 129.90,
                'category' => 'armor',
                'stock' => 3,
                'level_requirement' => 85,
                'rarity' => 'epic',
                'features' => ['+2000 Defesa', '+15% HP', 'Resistência ao Fogo', 'Imunidade a Veneno'],
                'is_featured' => true,
                'is_active' => true,
                'type' => 'item'
            ],
            [
                'name' => 'Anel do Poder Supremo',
                'description' => 'Anel místico que amplifica todas as habilidades do usuário. Dizem que foi criado pelos deuses antigos.',
                'price' => 199.90,
                'category' => 'accessories',
                'stock' => 2,
                'level_requirement' => 90,
                'rarity' => 'mythic',
                'features' => ['+30% Todos os Atributos', '+50% Mana', 'Regeneração HP/MP', 'Teleporte'],
                'is_featured' => true,
                'is_active' => true,
                'type' => 'item'
            ],
            [
                'name' => 'Poção da Imortalidade',
                'description' => 'Poção rara que concede invulnerabilidade temporária. Útil em batalhas épicas contra bosses.',
                'price' => 45.90,
                'category' => 'consumables',
                'stock' => 15,
                'level_requirement' => 50,
                'rarity' => 'rare',
                'features' => ['Invulnerabilidade 30s', 'Cura Completa', 'Boost de Velocidade', 'Sem Cooldown'],
                'is_featured' => true,
                'is_active' => true,
                'type' => 'item'
            ],
            [
                'name' => 'Cristal de Energia Pura',
                'description' => 'Cristal que contém energia pura dos elementos. Essencial para forjar itens lendários.',
                'price' => 75.90,
                'category' => 'materials',
                'stock' => 8,
                'level_requirement' => 60,
                'rarity' => 'rare',
                'features' => ['Material de Forja', 'Energia Elemental', 'Raridade Alta', 'Valor de Troca'],
                'is_featured' => true,
                'is_active' => true,
                'type' => 'item'
            ],
            [
                'name' => 'Orbe do Destino',
                'description' => 'Orbe místico que permite ao usuário alterar o destino. Item único e extremamente poderoso.',
                'price' => 299.90,
                'category' => 'special',
                'stock' => 1,
                'level_requirement' => 100,
                'rarity' => 'mythic',
                'features' => ['Alterar Destino', 'Poder Absoluto', 'Item Único', 'Efeitos Aleatórios'],
                'is_featured' => true,
                'is_active' => true,
                'type' => 'item'
            ]
        ];

        foreach ($featuredProducts as $productData) {
            Product::create($productData);
        }
    }
}
