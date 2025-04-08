<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;

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

        // Create business user with associated business
        $business = Business::factory()->create([
            'name' => 'Sample Business',
            'domain' => 'sample.debazaar.test',
            'kvk_number' => '12345678',
            'vat_number' => 'NL123456789B01',
            'custom_url' => 'sample-business',
            'theme_settings' => json_encode([
                'primary_color' => '#3b82f6',
                'secondary_color' => '#1d4ed8',
                'font_family' => 'Inter',
            ])
        ]);

        User::factory()->create([
            'name' => 'Business Owner',
            'email' => 'business@debazaar.nl',
            'role' => 'business',
            'business_id' => $business->id,
        ]);
    }
}
