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
            'unit' => 'unit',
            'quantity' => 12,
            'standard_level' => 5,
            'unit_cost' => 85000,
            'price' => 132000,
            'weight' => 45,
            'warranty_months' => 1,
        ]);

        Product::create([
            'item_code' => 'MCH-002',
            'name' => 'Cacao Winnower',
            'category' => 'machines',
            'unit' => 'unit',
            'quantity' => 8,
            'standard_level' => 5,
            'unit_cost' => 65000,
            'price' => 85080,
            'weight' => 30,
            'warranty_months' => 1,
        ]);

        Product::create([
            'item_code' => 'MCH-003',
            'name' => 'Cacao Grinder',
            'category' => 'machines',
            'unit' => 'unit',
            'quantity' => 5,
            'standard_level' => 5,
            'unit_cost' => 70000,
            'price' => 95000,
            'weight' => 25,
            'warranty_months' => 1,
        ]);

        Product::create([
            'item_code' => 'MCH-004',
            'name' => 'Hydraulic Cacao Press',
            'category' => 'machines',
            'unit' => 'unit',
            'quantity' => 3,
            'standard_level' => 5,
            'unit_cost' => 110000,
            'price' => 150000,
            'weight' => 60,
            'warranty_months' => 1,
        ]);

        // TOOLS
        Product::create([
            'item_code' => 'TOL-001',
            'name' => 'Cacao Scoop',
            'category' => 'tools',
            'unit' => 'piece',
            'quantity' => 24,
            'standard_level' => 10,
            'unit_cost' => 250,
            'price' => 350,
            'weight' => 0.2,
            'warranty_months' => null,
        ]);

        Product::create([
            'item_code' => 'TOL-002',
            'name' => 'Digital Weighing Scale',
            'category' => 'tools',
            'unit' => 'piece',
            'quantity' => 6,
            'standard_level' => 5,
            'unit_cost' => 1800,
            'price' => 2500,
            'weight' => 1.5,
            'warranty_months' => 1,
        ]);

        Product::create([
            'item_code' => 'TOL-003',
            'name' => 'Stainless Work Table',
            'category' => 'tools',
            'unit' => 'piece',
            'quantity' => 2,
            'standard_level' => 3,
            'unit_cost' => 4000,
            'price' => 6800,
            'weight' => 20,
            'warranty_months' => null,
        ]);

        // ACCESSORIES
        Product::create([
            'item_code' => 'ACC-001',
            'name' => 'Protective Gloves',
            'category' => 'accessories',
            'unit' => 'pair',
            'quantity' => 0,
            'standard_level' => 10,
            'unit_cost' => 80,
            'price' => 150,
            'weight' => 0.1,
            'warranty_months' => null,
        ]);

        Product::create([
            'item_code' => 'ACC-002',
            'name' => 'Hammer Flake of 100',
            'category' => 'accessories',
            'unit' => 'pack',
            'quantity' => 28,
            'standard_level' => 10,
            'unit_cost' => 210,
            'price' => 300,
            'weight' => 2,
            'warranty_months' => null,
        ]);
    }
}