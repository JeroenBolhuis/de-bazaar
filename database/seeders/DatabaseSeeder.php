<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed basic data
        $this->call([
            UserTypeSeeder::class,
            RoleSeeder::class,
            CompanySeeder::class,
        ]);

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@debazaar.nl',
            'user_type_id' => UserType::where('slug', 'regular')->first()->id,
        ]);
        $admin->roles()->attach(Role::where('slug', 'admin')->first());

        // Create regular user
        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@debazaar.nl',
            'user_type_id' => UserType::where('slug', 'regular')->first()->id,
        ]);
        $user->roles()->attach(Role::where('slug', 'user')->first());

        // Create private seller
        $privateSeller = User::factory()
            ->privateAdvertiser()
            ->create([
                'name' => 'Private Seller',
                'email' => 'seller@debazaar.nl',
                'user_type_id' => UserType::where('slug', 'private')->first()->id,
            ]);
        $privateSeller->roles()->attach(Role::where('slug', 'private-seller')->first());

        // Create business owners for each company
        $companies = Company::all();
        foreach ($companies as $index => $company) {
            $businessOwner = User::factory()
                ->business()
                ->create([
                    'name' => "Business Owner " . ($index + 1),
                    'email' => "owner{$index}@{$company->domain}",
                    'user_type_id' => UserType::where('slug', 'business')->first()->id,
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                ]);
            $businessOwner->roles()->attach(Role::where('slug', 'business-owner')->first());

            // Create some employees for each company
            User::factory()
                ->business()
                ->count(2)
                ->create([
                    'user_type_id' => UserType::where('slug', 'business')->first()->id,
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                ])
                ->each(function ($employee) {
                    $employee->roles()->attach(Role::where('slug', 'business-employee')->first());
                });
        }

        // Create some additional regular users
        User::factory()
            ->count(10)
            ->create([
                'user_type_id' => UserType::where('slug', 'regular')->first()->id,
            ])
            ->each(function ($user) {
                $user->roles()->attach(Role::where('slug', 'user')->first());
            });

        // Create some additional private sellers
        User::factory()
            ->privateAdvertiser()
            ->count(5)
            ->create([
                'user_type_id' => UserType::where('slug', 'private')->first()->id,
            ])
            ->each(function ($seller) {
                $seller->roles()->attach(Role::where('slug', 'private-seller')->first());
            });
    }
}
