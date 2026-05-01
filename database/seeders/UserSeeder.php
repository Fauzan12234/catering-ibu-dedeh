<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Bapak Master',
                'username' => 'master',
                'password_hash' => Hash::make('rahasia123'),
                'role' => 'master_admin',
                'is_active' => true,
            ],
            [
                'name' => 'Staf Admin',
                'username' => 'admin',
                'password_hash' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        ]);
    }
}