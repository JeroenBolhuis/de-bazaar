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

        // Genereer bijvoorbeeld 50 advertenties voor elke gebruiker met de rol 'seller' of 'business'
        $sellerAndBusinessUsers = $users->filter(function ($user) {
            return $user->role === 'seller' || $user->role === 'business' || $user->role === 'admin';
        });

        foreach ($sellerAndBusinessUsers as $user) {
            Advertisement::factory()->count(random_int(0, 4))->create([
                'user_id' => $user->id, // Koppel aan de huidige gebruiker
                'type' => 'listing',
            ]);
            Advertisement::factory()->count(random_int(0, 4))->create([
                'user_id' => $user->id, // Koppel aan de huidige gebruiker
                'type' => 'auction',
            ]);
            Advertisement::factory()->count(random_int(0, 4))->create([
                'user_id' => $user->id, // Koppel aan de huidige gebruiker
                'type' => 'rental',
            ]);
        }
    }
}

