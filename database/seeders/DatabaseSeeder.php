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
//        \App\Models\Thread::factory(6)->create();
//        for ($i = 0; $i < 20; ++$i) {
//            \App\Models\Message::factory()
//                ->create(
//                    ['sender_id' => rand(1, 15), 'thread_id' => rand(1, 6), 'content' => 'hello']
//                );
//        }
    }
}
