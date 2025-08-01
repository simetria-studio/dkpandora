<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Imagens de exemplo para produtos em destaque
        $productImages = [
            'Espada Lendária +15' => 'https://via.placeholder.com/400x300/8B0000/FFFFFF?text=Espada+Lendaria',
            'Armadura Épica do Dragão' => 'https://via.placeholder.com/400x300/4169E1/FFFFFF?text=Armadura+Epica',
            'Anel do Poder Supremo' => 'https://via.placeholder.com/400x300/FFD700/000000?text=Anel+do+Poder',
            'Poção da Imortalidade' => 'https://via.placeholder.com/400x300/32CD32/FFFFFF?text=Pocao+Imortalidade',
            'Cristal de Energia Pura' => 'https://via.placeholder.com/400x300/9370DB/FFFFFF?text=Cristal+Energia',
            'Orbe do Destino' => 'https://via.placeholder.com/400x300/FF4500/FFFFFF?text=Orbe+Destino'
        ];

        foreach ($productImages as $productName => $imageUrl) {
            $product = Product::where('name', $productName)->first();
            if ($product) {
                $product->update(['image' => $imageUrl]);
            }
        }

        // Adicionar imagens para alguns produtos normais também
        $normalProductImages = [
            'Espada de Ferro' => 'https://via.placeholder.com/400x300/696969/FFFFFF?text=Espada+Ferro',
            'Poção de Cura' => 'https://via.placeholder.com/400x300/228B22/FFFFFF?text=Pocao+Cura',
            'Armadura de Couro' => 'https://via.placeholder.com/400x300/8B4513/FFFFFF?text=Armadura+Couro'
        ];

        foreach ($normalProductImages as $productName => $imageUrl) {
            $product = Product::where('name', $productName)->first();
            if ($product) {
                $product->update(['image' => $imageUrl]);
            }
        }
    }
}
