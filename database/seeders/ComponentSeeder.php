<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Component;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $components = [
            ['type' => 'featured_advertisements', 'label' => 'Featured Advertisements'],
            ['type' => 'introduction_text', 'label' => 'Introduction Text'],
            ['type' => 'image', 'label' => 'Image'],
        ];

        foreach ($components as $component) {
            Component::create($component);
        }
    }
} 