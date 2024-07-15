<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UseradminwoeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert contoh data ke dalam tabel users
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'tripujiantoro',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('123'), // Ganti dengan password yang diinginkan
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
          
            // Tambahkan data pengguna lainnya di sini
        ]);
    }
}
