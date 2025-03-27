<?php

namespace Database\Factories;

use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    protected $model = Advertisement::class;

    public function definition(): array
    {
        return [
            // Vul alleen de velden in die niet afhankelijk zijn van Seeder
            // user_id wordt vanuit Seeder gezet
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'type' => $this->faker->randomElement(['listing', 'rental', 'auction']),
            'image' => null,
            'is_active' => true,
            
            // auction specific fields
            'auction_start_date' => null,
            'auction_end_date' => null,
            'auction_start_price' => 0,
            
            // rental specific fields
            'condition' => 100,
            'wear_per_day' => 0,
        ];
    }
}
