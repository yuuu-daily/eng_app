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
            'name' => 'user',
            'email' => 'user@sample.com',
            'password' => '$2y$12$UYS4FUuk3JE.CzyF54V7KeFkW9gNi1kzjfFLbj/aemYiTXU3Q0QFK'
        ]);
    }
}
