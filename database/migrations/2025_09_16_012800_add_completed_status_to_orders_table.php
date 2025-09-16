<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adicionar 'completed' ao ENUM de status
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'paid', 'delivered', 'cancelled', 'completed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover 'completed' do ENUM de status
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'paid', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
};
