<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full system access',
            ],
            [
                'name' => 'Moderator',
                'slug' => 'moderator',
                'description' => 'Can moderate listings, reviews, and users',
            ],
            [
                'name' => 'Business Owner',
                'slug' => 'business-owner',
                'description' => 'Can manage company settings and employees',
            ],
            [
                'name' => 'Business Employee',
                'slug' => 'business-employee',
                'description' => 'Can manage company listings and orders',
            ],
            [
                'name' => 'Private Seller',
                'slug' => 'private-seller',
                'description' => 'Can create and manage personal listings',
            ],
            [
                'name' => 'Regular User',
                'slug' => 'user',
                'description' => 'Basic user privileges',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
} 