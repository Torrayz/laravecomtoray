<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductimgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert contoh data ke dalam tabel product_images
        DB::table('product_images')->insert([
            [
                'id' => 1,
                'product_id' => 1,
                'path' => 'nasi.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'product_id' => 2,
                'path' => 'gacoan.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'product_id' => 3,
                'path' => 'dompet.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'product_id' => 4,
                'path' => 'tas.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'product_id' => 5,
                'path' => 'j2prime.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'product_id' => 6,
                'path' => 'xiami.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
