<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Alterar a coluna rarity para incluir as novas raridades
            $table->enum('rarity', [
                'common',        // Cinza
                'uncommon',      // Branco
                'rare',          // Verde
                'epic',          // Azul
                'legendary',     // Laranja
                'mythic',        // Amarelo
                'divine',        // Roxo
                'transcendent'   // Vermelho
            ])->default('common')->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverter para as raridades originais
            $table->enum('rarity', ['common', 'rare', 'epic', 'legendary'])
                  ->default('common')->change();
        });
    }
};
