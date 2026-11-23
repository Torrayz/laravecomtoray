<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopsSeeder extends Seeder
{
    public function run()
    {
        DB::table('shops')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'name_shop' => 'Torray Store',
                'desc' => 'Curated everyday electronics, accessories, and pantry essentials with transparent stock tracking.',
                'phone' => 81314234712,
                'address' => 'Tangerang, Indonesia',
                'path' => 'tripujiantoro12@gmail.com-JzjiP2PDA8CD3szqcTYf.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
