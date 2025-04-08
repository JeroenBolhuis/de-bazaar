<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertisement;
use App\Models\Business;
use App\Models\User;

class AdvertisementBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all businesses
        $businesses = Business::all();
        
        foreach ($businesses as $business) {
            // Get users that belong to this business
            $businessUsers = User::where('business_id', $business->id)->pluck('id');
            
            if ($businessUsers->isNotEmpty()) {
                // Get advertisements created by users of this business that don't have a business_id yet
                $advertisements = Advertisement::whereIn('user_id', $businessUsers)
                    ->whereNull('business_id')
                    ->take(5)
                    ->get();
                
                // Update each advertisement with the business_id
                foreach ($advertisements as $advertisement) {
                    $advertisement->update([
                        'business_id' => $business->id
                    ]);
                }
            }
        }
    }
} 