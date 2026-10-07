<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // MACHINES
        Product::create([
            'item_code' => 'MCH-001',
            'name' => 'Cacao Roaster',
            'category' => 'machines',
            'quantity' => 12,
            'standard_level' => 5,
            'unit_cost' => 85000,
            'price' => 132000,
        ]);

        Product::create([
            'item_code' => 'MCH-002',
            'name' => 'Cacao Winnower',
            'category' => 'machines',
            'quantity' => 8,
            'standard_level' => 5,
            'unit_cost' => 65000,
            'price' => 85080,
        ]);

        Product::create([
            'item_code' => 'MCH-003',
            'name' => 'Cacao Grinder',
            'category' => 'machines',
            'quantity' => 5,
            'standard_level' => 5,
            'unit_cost' => 70000,
            'price' => 95000,
        ]);

        Product::create([
            'item_code' => 'MCH-004',
            'name' => 'Hydraulic Cacao Press',
            'category' => 'machines',
            'quantity' => 3,
            'standard_level' => 5,
            'unit_cost' => 110000,
            'price' => 150000,
        ]);

        // TOOLS
        Product::create([
            'item_code' => 'TOL-001',
            'name' => 'Cacao Scoop',
            'category' => 'tools',
            'quantity' => 24,
            'standard_level' => 10,
            'unit_cost' => 250,
            'price' => 350,
        ]);

        Product::create([
            'item_code' => 'TOL-002',
            'name' => 'Digital Weighing Scale',
            'category' => 'tools',
            'quantity' => 6,
            'standard_level' => 5,
            'unit_cost' => 1800,
            'price' => 2500,
        ]);

        Product::create([
            'item_code' => 'TOL-003',
            'name' => 'Stainless Work Table',
            'category' => 'tools',
            'quantity' => 2,
            'standard_level' => 3,
            'unit_cost' => 4000,
            'price' => 6800,
        ]);

        // ACCESSORIES
        Product::create([
            'item_code' => 'ACC-001',
            'name' => 'Protective Gloves',
            'category' => 'accessories',
            'quantity' => 0,
            'standard_level' => 10,
            'unit_cost' => 80,
            'price' => 150,
        ]);

        Product::create([
            'item_code' => 'ACC-002',
            'name' => 'Hammer Flake of 100',
            'category' => 'accessories',
            'quantity' => 28,
            'standard_level' => 10,
            'unit_cost' => 210,
            'price' => 300,
        ]);
    }
}