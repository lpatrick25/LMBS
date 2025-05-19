<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories seeding
        DB::table('categories')->insert([
            [
                'category_id' => 1,
                'category_name' => 'Mixer',
                'category_type' => 'Equipment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'category_name' => 'Glass',
                'category_type' => 'Equipment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'category_name' => 'Test Tube Rack',
                'category_type' => 'Apparatus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'category_name' => 'Beaker',
                'category_type' => 'Apparatus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'category_name' => 'Knife',
                'category_type' => 'Tools',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'category_name' => 'Bunsen Burner',
                'category_type' => 'Equipment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'category_name' => 'Spatula',
                'category_type' => 'Tools',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 8,
                'category_name' => 'Gas Range',
                'category_type' => 'Equipment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
