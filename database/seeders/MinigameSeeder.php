<?php

namespace Database\Seeders;

use App\Models\MinigameRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class MinigameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // 50% chance for each game
            if (rand(0, 1)) {
                // Quick Click game
                MinigameRecord::create([
                    'user_id' => $user->id,
                    'game_type' => 'game1',
                    'score' => rand(-5, 10)
                ]);
            }

            if (rand(0, 1)) {
                // Number Memory game
                MinigameRecord::create([
                    'user_id' => $user->id,
                    'game_type' => 'game2',
                    'score' => rand(2, 9)
                ]);
            }

            if (rand(0, 1)) {
                // Pattern Match game
                MinigameRecord::create([
                    'user_id' => $user->id,
                    'game_type' => 'game3',
                    'score' => rand(3, 12)
                ]);
            }

            if (rand(0, 1)) {
                // True/False game
                MinigameRecord::create([
                    'user_id' => $user->id,
                    'game_type' => 'game4',
                    'score' => rand(4, 17)
                ]);
            }
        }
    }
} 