<?php

namespace Database\Seeders;
// database/seeders/BusinessComponentSeeder.php

use Illuminate\Database\Seeder;
use App\Models\BusinessComponent;
use App\Models\Business;

class BusinessComponentSeeder extends Seeder
{
    public function run()
    {
        $business = Business::first(); // or findOrFail(id)

        BusinessComponent::create([
            'business_id' => $business->id,
            'type' => 'intro_text',
            'order' => 1,
            'content' => 'Welcome to our business page. We offer great products!',
        ]);

        BusinessComponent::create([
            'business_id' => $business->id,
            'type' => 'image',
            'order' => 2,
            'image_path' => 'business_images/sample.jpg',
        ]);

        BusinessComponent::create([
            'business_id' => $business->id,
            'type' => 'featured_ads',
            'order' => 3,
        ]);
    }
}
