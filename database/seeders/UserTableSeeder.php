<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = DB::table('users')->insert([
            'user_id' => 'ADM-001',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'user_role' => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
