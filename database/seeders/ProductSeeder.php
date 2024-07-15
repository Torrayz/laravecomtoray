<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert data ke dalam tabel products
        DB::table('products')->insert([
            [
                'id' => 1,
                'category_id' => 2,
                'title' => 'nasi goreng',
                'desc' => 'ini nasi goreng',
                'price' => 2000,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category_id' => 2,
                'title' => 'gacoan',
                'desc' => 'ini gacoan',
                'price' => 2000,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'category_id' => 3,
                'title' => 'dompet',
                'desc' => 'ini dompet men',
                'price' => 2000,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'category_id' => 3,
                'title' => 'tas mahal cuy',
                'desc' => 'beli tas dapet cewenya',
                'price' => 2000,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'category_id' => 1,
                'title' => 'samsung j2 prime',
                'desc' => 'hp gamink',
                'price' => 2000,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'category_id' => 1,
                'title' => 'xiaumi',
                'desc' => 'hp cina',
                'price' => 2000,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
