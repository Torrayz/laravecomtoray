<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'category_id' => 2,
                'title' => 'house-special-fried-rice',
                'desc' => 'Ready-to-serve aromatic fried rice prepared with toasted garlic, shallots, and signature seasoning.',
                'price' => 12,
                'stock' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category_id' => 2,
                'title' => 'spicy-noodle-bowl',
                'desc' => 'Savory springy noodles tossed in chili oil, fried shallots, and crisp wonton topping.',
                'price' => 10,
                'stock' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'category_id' => 3,
                'title' => 'classic-bifold-wallet',
                'desc' => 'Slim everyday bifold wallet crafted with structured stitching and six card slots.',
                'price' => 28,
                'stock' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'category_id' => 3,
                'title' => 'everyday-structured-handbag',
                'desc' => 'Minimalist carryall tote with reinforced handles, interior zip pocket, and water-resistant lining.',
                'price' => 64,
                'stock' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'category_id' => 1,
                'title' => 'galaxy-essential-smartphone',
                'desc' => 'Compact unlocked smartphone with crisp display, expandable storage, and reliable all-day battery.',
                'price' => 145,
                'stock' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'category_id' => 1,
                'title' => 'redmi-daily-smartphone',
                'desc' => 'High-value dual-SIM smartphone featuring fast charging, vivid screen, and responsive performance.',
                'price' => 189,
                'stock' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
