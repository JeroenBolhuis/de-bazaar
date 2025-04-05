<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the business user
        $businessUser = User::where('email', 'business@debazaar.nl')->first();

        // Create business for the business user
        $business = Business::factory()->create([
            'name' => 'Tech Traders',
            'domain' => 'techtraders',
            'kvk_number' => '12345678',
            'vat_number' => 'NL123456789B01',
            'theme_settings' => json_encode([
                'primary_color' => '#2563eb',
                'secondary_color' => '#1e40af',
                'font_family' => 'Inter',
            ]),
        ]);

        // Update the business user to link to the business
        $businessUser->update(['business_id' => $business->id]);

        // Create additional businesses
        Business::factory()->create([
            'name' => 'Home & Garden Plus',
            'domain' => 'homeandgarden',
            'kvk_number' => '87654321',
            'vat_number' => 'NL987654321B01',
            'theme_settings' => json_encode([
                'primary_color' => '#16a34a',
                'secondary_color' => '#15803d',
                'font_family' => 'Inter',
            ])
        ]);

        Business::factory()->create([
            'name' => 'Vintage Collectibles',
            'domain' => 'vintage',
            'kvk_number' => '11223344',
            'vat_number' => 'NL112233445B01',
            'theme_settings' => json_encode([
                'primary_color' => '#9333ea',
                'secondary_color' => '#7e22ce',
                'font_family' => 'Inter',
            ])
        ]);
    }
} 