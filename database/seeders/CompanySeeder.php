<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Tech Traders',
                'slug' => 'tech-traders',
                'domain' => 'techtraders.debazaar.test',
                'description' => 'Specialized in trading tech gadgets and electronics',
                'theme_settings' => json_encode([
                    'primary_color' => '#2563eb',
                    'secondary_color' => '#1e40af',
                    'accent_color' => '#3b82f6',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Home & Garden Plus',
                'slug' => 'home-garden-plus',
                'domain' => 'homeandgarden.debazaar.test',
                'description' => 'Everything for your home and garden',
                'theme_settings' => json_encode([
                    'primary_color' => '#16a34a',
                    'secondary_color' => '#15803d',
                    'accent_color' => '#22c55e',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Vintage Collectibles',
                'slug' => 'vintage-collectibles',
                'domain' => 'vintage.debazaar.test',
                'description' => 'Rare and vintage collectible items',
                'theme_settings' => json_encode([
                    'primary_color' => '#9333ea',
                    'secondary_color' => '#7e22ce',
                    'accent_color' => '#a855f7',
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
} 