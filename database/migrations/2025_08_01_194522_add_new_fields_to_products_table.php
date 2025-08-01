<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Renomear level_required para level_requirement
            $table->renameColumn('level_required', 'level_requirement');

            // Adicionar novos campos
            $table->json('features')->nullable()->after('level_requirement');
            $table->boolean('is_featured')->default(false)->after('features');

            // Atualizar campo rarity para incluir novos valores
            $table->string('rarity')->default('common')->change();

            // Atualizar campo image para aceitar caminhos de arquivo
            $table->string('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Remover novos campos
            $table->dropColumn(['features', 'is_featured']);

            // Renomear level_requirement de volta para level_required
            $table->renameColumn('level_requirement', 'level_required');
        });
    }
};
