<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'kvk_number' => fake()->numberBetween(10000000, 99999999),
            'vat_number' => fake()->numberBetween(10000000, 99999999),
            'domain' => fake()->domainName(),
            'theme_settings' => null,
        ];
    }
}
