<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@debazaar.nl',
            'role' => 'admin',
        ]);

        // Create regular user
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@debazaar.nl',
            'role' => 'user',
        ]);

        // Create private seller
        User::factory()->create([
            'name' => 'Private Seller',
            'email' => 'seller@debazaar.nl',
            'role' => 'seller',
        ]);

        // Create business user
        User::factory()->create([
            'name' => 'Business Owner',
            'email' => 'business@debazaar.nl',
            'role' => 'business',
        ]);
    }
}
