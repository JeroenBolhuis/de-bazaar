<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public static $businessUserId;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@debazaar.nl',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@debazaar.nl',
            'role' => 'user',
        ]);

        User::factory()->create([
            'name' => 'Private Seller',
            'email' => 'seller@debazaar.nl',
            'role' => 'seller',
        ]);

        $businessUser = User::factory()->create([
            'name' => 'Business Owner',
            'email' => 'business@debazaar.nl',
            'role' => 'business',
        ]);

        self::$businessUserId = $businessUser->id;
    }
}
