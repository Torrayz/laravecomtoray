<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'shop_id' => 1,
                'name' => 'electronics',
                'path' => 'R.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'shop_id' => 1,
                'name' => 'food-and-beverages',
                'path' => 'Designer.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'shop_id' => 1,
                'name' => 'bags-and-wallets',
                'path' => 'bag&wallet.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
