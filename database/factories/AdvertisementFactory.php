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
            
            'auction_start_date' => $this->faker->date,
            'auction_end_date' => $this->faker->date,
            
            // rental specific fields
            'condition' => $this->faker->numberBetween(0, 100),
            'wear_per_day' => $this->faker->numberBetween(0, 50),
        ];
    }
}
