<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image',
        'stock',
        'type', // 'item' ou 'gold'
        'rarity', // 'common', 'rare', 'epic', 'legendary'
        'level_requirement',
        'features',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'features' => 'array'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeItems($query)
    {
        return $query->where('type', 'item');
    }

    public function scopeGold($query)
    {
        return $query->where('type', 'gold');
    }

    // Accessors para labels
    public function getCategoryLabelAttribute()
    {
        $categories = [
            'weapons' => 'Armas',
            'armor' => 'Armaduras',
            'accessories' => 'Acessórios',
            'consumables' => 'Consumíveis',
            'materials' => 'Materiais',
            'special' => 'Itens Especiais'
        ];

        return $categories[$this->category] ?? $this->category;
    }

    public function getRarityLabelAttribute()
    {
        $rarities = [
            'common' => 'Cinza',
            'uncommon' => 'Branco',
            'rare' => 'Verde',
            'epic' => 'Azul',
            'legendary' => 'Laranja',
            'mythic' => 'Amarelo',
            'divine' => 'Roxo',
            'transcendent' => 'Vermelho'
        ];

        return $rarities[$this->rarity] ?? $this->rarity;
    }

    public function getRarityColorAttribute()
    {
        $colors = [
            'common' => 'secondary',      // Cinza
            'uncommon' => 'light',        // Branco
            'rare' => 'success',          // Verde
            'epic' => 'info',             // Azul
            'legendary' => 'warning',     // Laranja
            'mythic' => 'warning',        // Amarelo
            'divine' => 'primary',        // Roxo
            'transcendent' => 'danger'    // Vermelho
        ];

        return $colors[$this->rarity] ?? 'secondary';
    }

    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock > 0) {
            return 'disponível';
        }
        return 'sem estoque';
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (Str::startsWith($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    public function hasImage()
    {
        return !empty($this->image);
    }
}
