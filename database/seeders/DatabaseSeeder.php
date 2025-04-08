<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed basic data (user types, roles, businesses first!)
        $this->call([
            UserSeeder::class,
            BusinessSeeder::class,
            AdvertisementSeeder::class,
            MinigameSeeder::class,
            ContractSeeder::class,
            ComponentSeeder::class,
            AdvertisementBusinessSeeder::class,
        ]);
    }
}
