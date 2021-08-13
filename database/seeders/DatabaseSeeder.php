<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        \App\Models\User::factory()->create(['name' => 'Jhon Doe', 'email' => 'test@example.com']);
        \App\Models\Question::factory(3)->create();
        \App\Models\Answer::factory(12)->create();
    }
}
