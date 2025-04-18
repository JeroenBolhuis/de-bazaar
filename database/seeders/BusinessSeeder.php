<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Business::factory()->create([
            'name' => 'Tech Traders',
            'domain' => 'techtraders.debazaar.test',
            'theme_settings' => json_encode([
                'primary_color' => '#2563eb',
                'secondary_color' => '#1e40af',
                'font_family' => 'Inter',
            ])
        ]);
        Business::factory()->create([
            'name' => 'Home & Garden Plus',
            'domain' => 'homeandgarden.debazaar.test',
            'theme_settings' => json_encode([
                'primary_color' => '#16a34a',
                'secondary_color' => '#15803d',
                'font_family' => 'Inter',
            ])
        ]);
        Business::factory()->create([
            'name' => 'Vintage Collectibles',
            'domain' => 'vintage.debazaar.test',
            'theme_settings' => json_encode([
                'primary_color' => '#9333ea',
                'secondary_color' => '#7e22ce',
                'font_family' => 'Inter',
            ])
        ]);
    }
} 