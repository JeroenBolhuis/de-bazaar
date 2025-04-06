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
    // database/factories/BusinessFactory.php

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'domain' => $this->faker->slug,
            'kvk_number' => $this->faker->randomNumber(8),
            'vat_number' => 'NL' . $this->faker->randomNumber(9) . 'B01',
            'theme_settings' => json_encode([
                'primary_color' => '#3B82F6',
                'secondary_color' => '#10B981',
                'font_family' => 'Inter',
            ]),
        ];
    }

}
