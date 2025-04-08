<?php

namespace Database\Factories;

use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    protected $model = Advertisement::class;

    private array $products = [
        'listing' => [
            ['title' => 'Vintage Levi\'s 501 Jeans', 'description' => 'Classic straight-leg denim jeans in excellent condition. Original American made, perfect vintage wash.', 'price' => 45.00],
            ['title' => 'Antique Brass Table Lamp', 'description' => 'Beautiful art deco style table lamp with original glass shade. Perfect working condition.', 'price' => 89.99],
            ['title' => 'Nintendo Switch OLED', 'description' => 'Like new Nintendo Switch OLED model with white Joy-Cons. Includes original box and accessories.', 'price' => 299.99],
            ['title' => 'Handmade Leather Wallet', 'description' => 'Genuine full-grain leather wallet, hand-stitched with premium waxed thread. Multiple card slots and bill compartment.', 'price' => 35.00],
            ['title' => 'Vintage Polaroid Camera', 'description' => 'Original Polaroid SX-70 from the 1970s. Fully tested and working. Great collector\'s item.', 'price' => 150.00],
        ],
        'auction' => [
            ['title' => 'Rare First Edition Book', 'description' => 'First edition of "The Great Gatsby" (1925). Good condition with original dust jacket.', 'price' => 1500.00],
            ['title' => 'Vintage Rolex Datejust', 'description' => '1985 Rolex Datejust 16234, silver dial, recently serviced with box and papers.', 'price' => 4500.00],
            ['title' => 'Original Apple-1 Computer', 'description' => 'Rare piece of computing history. Fully documented provenance. Working condition.', 'price' => 150000.00],
            ['title' => 'Ancient Roman Coin', 'description' => 'Authentic Roman denarius from 120 AD. Emperor Hadrian. Excellent condition.', 'price' => 750.00],
            ['title' => 'Signed Beatles Album', 'description' => 'Original vinyl of "Abbey Road" signed by Paul McCartney and Ringo Starr. Includes certificate of authenticity.', 'price' => 2500.00],
        ],
        'rental' => [
            ['title' => 'Professional Canon DSLR Kit', 'description' => 'Canon 5D Mark IV with 24-70mm f/2.8 lens. Perfect for events and professional shoots.', 'price' => 75.00],
            ['title' => 'Mountain Bike - Trek Fuel EX 9.8', 'description' => 'High-end full suspension mountain bike. Size Large. Perfect for trail riding.', 'price' => 45.00],
            ['title' => 'DJ Equipment Set', 'description' => 'Complete DJ setup including Pioneer CDJs and DJM mixer. Perfect for events.', 'price' => 150.00],
            ['title' => 'Luxury Evening Gown', 'description' => 'Designer evening gown, size 8. Perfect for special occasions. Original price $2000.', 'price' => 95.00],
            ['title' => 'Professional Power Tools Set', 'description' => 'Complete set of DeWalt power tools including drill, saw, and sander.', 'price' => 35.00],
        ],
    ];

    public function definition(): array
    {
        $type = $this->faker->randomElement(['listing', 'rental', 'auction']);
        $product = $this->faker->randomElement($this->products[$type]);
        
        return [
            'title' => $product['title'],
            'description' => $product['description'],
            'price' => $product['price'],
            'type' => $type,
            'image' => null,
            'is_active' => true,
            'auction_start_date' => $type === 'auction' ? now() : null,
            'auction_end_date' => $type === 'auction' ? now()->addDays(7) : null,
            'condition' => $type === 'rental' ? $this->faker->numberBetween(85, 100) : 100,
            'wear_per_day' => $type === 'rental' ? $this->faker->numberBetween(1, 5) : 0,
        ];
    }
}
