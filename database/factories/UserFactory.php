<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            
            // Contact information
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'country' => 'Netherlands',
            
            // Business information (nullable)
            'company_name' => null,
            'kvk_number' => null,
            'vat_number' => null,
            
            // Preferences
            'language' => 'nl',
            'preferences' => null,
            
            // Stats and ratings
            'rating' => fake()->randomFloat(2, 3, 5),
            'total_reviews' => fake()->numberBetween(0, 100),
            'successful_sales' => fake()->numberBetween(0, 50),
            'successful_rentals' => fake()->numberBetween(0, 30),
            'successful_auctions' => fake()->numberBetween(0, 20),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Configure the model as a business user.
     */
    public function business(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_name' => fake()->company(),
            'kvk_number' => fake()->numerify('########'),
            'vat_number' => 'NL' . fake()->numerify('#########') . 'B01',
            'contract_approved' => true,
            'contract_approved_at' => now(),
        ]);
    }

    /**
     * Configure the model as a private advertiser.
     */
    public function privateAdvertiser(): static
    {
        return $this->state(fn (array $attributes) => [
            'contract_approved' => true,
            'contract_approved_at' => now(),
        ]);
    }
}
