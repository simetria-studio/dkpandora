<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Criar usuário administrador
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@dkpandora.com',
            'password' => bcrypt('admin123'),
            'is_admin' => true,
        ]);

        $this->call([
            ProductSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
