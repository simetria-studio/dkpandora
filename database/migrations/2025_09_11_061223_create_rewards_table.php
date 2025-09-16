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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('required_amount', 10, 2);
            $table->string('reward_type'); // 'discount', 'product', 'bonus', etc.
            $table->json('reward_data')->nullable(); // dados específicos da recompensa
            $table->boolean('is_active')->default(true);
            $table->integer('max_redemptions')->nullable(); // null = ilimitado
            $table->integer('current_redemptions')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
