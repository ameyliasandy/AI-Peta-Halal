<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name'       => 'Admin HalalFood',
            'email'      => 'admin@halalfood.com',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'no_hp'      => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}