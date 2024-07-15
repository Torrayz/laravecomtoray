<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert data ke table categories
        DB::table('categories')->insert([
            [
                'id' => 1,
                'shop_id' => 1,
                'name' => 'Gadget',
                'path' => ('R.jpeg'),
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'id' => 2,
                'shop_id' => 1,
                'name' => 'Makanan-dan-minuman',
                'path' =>  ('Designer.png'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'shop_id' => 1,
                'name' => 'bag-dan-wallet',
                'path' => ('bag&wallet.png'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
            
        ]);
    }
}
