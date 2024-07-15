<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShopsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert data ke table shops
        DB::table('shops')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'name_shop' => 'Torray store',
                'desc' => 'Kita menjual semua hal yang anda butuhkan',
                'phone' => 81314234712,
                'address' => 'ciledug',
                'path' => 'tripujiantoro12@gmail.com-JzjiP2PDA8CD3szqcTYf.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
