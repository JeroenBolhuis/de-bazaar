<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Regular User',
                'slug' => 'regular',
                'description' => 'Regular user without advertising capabilities',
                'can_advertise' => false,
                'is_business' => false,
                'max_listings' => 0,
                'max_rental_listings' => 0,
                'max_auction_listings' => 0,
            ],
            [
                'name' => 'Private Advertiser',
                'slug' => 'private',
                'description' => 'Private user with advertising capabilities',
                'can_advertise' => true,
                'is_business' => false,
                'max_listings' => 4,
                'max_rental_listings' => 4,
                'max_auction_listings' => 4,
            ],
            [
                'name' => 'Business Advertiser',
                'slug' => 'business',
                'description' => 'Business user with extended advertising capabilities',
                'can_advertise' => true,
                'is_business' => true,
                'max_listings' => 100,
                'max_rental_listings' => 100,
                'max_auction_listings' => 100,
            ],
        ];

        foreach ($types as $type) {
            UserType::create($type);
        }
    }
} 