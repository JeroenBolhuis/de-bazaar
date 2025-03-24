<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Advertisement;

class AdvertisementSeeder extends Seeder
{
    public function run()
    {
        // Zorg ervoor dat er users bestaan
        $users = User::all();

        if ($users->count() === 0) {
            $this->command->info('No users found, skipping advertisements seeding.');
            return;
        }

        // Genereer bijvoorbeeld 50 advertenties
        Advertisement::factory()->count(50)->create([
            'user_id' => $users->random()->id, // Koppel random aan bestaande user
        ]);
    }
}

