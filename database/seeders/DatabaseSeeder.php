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
        // Seed basic data (user types, roles, companies first!)
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            AdvertisementSeeder::class,
        ]);
    }
}
