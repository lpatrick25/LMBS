<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Items seeding
        DB::table('items')->insert([
            // HM Laboratory - Equipments
            [
                'item_name' => 'Stand Mixer',
                'category_id' => 1,
                'beginning_qty' => 3,
                'current_qty' => 3,
                'laboratory' => 'HM Laboratory',
                'description' => 'A kitchen appliance used for mixing, beating, and kneading dough or batter.',
                'image' => 'dist/img/mixer.jpg',
            ],
            [
                'item_name' => 'Wine Glass Set',
                'category_id' => 2,
                'current_qty' => 12,
                'beginning_qty' => 12,
                'laboratory' => 'HM Laboratory',
                'description' => 'A set of glasses designed for serving wine, enhancing its aroma and flavor.',
                'image' => 'dist/img/wine_glass.jpg',
            ],
            [
                'item_name' => 'Gas Range',
                'category_id' => 8,
                'beginning_qty' => 2,
                'current_qty' => 2,
                'laboratory' => 'HM Laboratory',
                'description' => 'A cooking appliance with burners and an oven powered by gas.',
                'image' => 'dist/img/gas_range.jpg',
            ],

            // HM Laboratory - Tools
            [
                'item_name' => 'Chef’s Knife',
                'category_id' => 5,
                'beginning_qty' => 5,
                'current_qty' => 5,
                'laboratory' => 'HM Laboratory',
                'description' => 'A versatile knife used for various food preparation tasks like slicing and chopping.',
                'image' => 'dist/img/knife.jpg',
            ],
            [
                'item_name' => 'Silicone Spatula',
                'category_id' => 7,
                'beginning_qty' => 8,
                'current_qty' => 8,
                'laboratory' => 'HM Laboratory',
                'description' => 'A heat-resistant tool used for mixing and scraping in cooking and baking.',
                'image' => 'dist/img/spatula.jpg',
            ],

            // Science Laboratory - Equipments
            [
                'item_name' => 'Bunsen Burner',
                'category_id' => 6,
                'beginning_qty' => 6,
                'current_qty' => 6,
                'laboratory' => 'Science Laboratory',
                'description' => 'A small adjustable gas burner used in laboratories for heating and combustion.',
                'image' => 'dist/img/bunsen_burner.jpg',
            ],

            // Science Laboratory - Apparatus
            [
                'item_name' => 'Test Tube Rack',
                'category_id' => 3,
                'beginning_qty' => 4,
                'current_qty' => 4,
                'laboratory' => 'Science Laboratory',
                'description' => 'A stand used to hold and organize test tubes in a laboratory.',
                'image' => 'dist/img/test_tube_rack.jpg',
            ],
            [
                'item_name' => 'Beaker 500ml',
                'category_id' => 4,
                'beginning_qty' => 10,
                'current_qty' => 10,
                'laboratory' => 'Science Laboratory',
                'description' => 'A cylindrical container used to measure, mix, and heat liquids in experiments.',
                'image' => 'dist/img/beaker.jpg',
            ],
            [
                'item_name' => 'Beaker 1000ml',
                'category_id' => 4,
                'beginning_qty' => 8,
                'current_qty' => 8,
                'laboratory' => 'Science Laboratory',
                'description' => 'A larger beaker used for mixing and measuring larger volumes of liquid.',
                'image' => 'dist/img/beaker_large.jpg',
            ],

            // Science Laboratory - Tools
            [
                'item_name' => 'Metal Spatula',
                'category_id' => 7,
                'beginning_qty' => 5,
                'current_qty' => 5,
                'laboratory' => 'Science Laboratory',
                'description' => 'A small, flat tool used for mixing and transferring substances in a lab.',
                'image' => 'dist/img/metal_spatula.jpg',
            ],

            // Additional items
            [
                'item_name' => 'Electric Oven',
                'category_id' => 1,
                'beginning_qty' => 1,
                'current_qty' => 1,
                'laboratory' => 'HM Laboratory',
                'description' => 'An electric appliance used for baking, roasting, and cooking.',
                'image' => 'dist/img/electric_oven.jpg',
            ],
            [
                'item_name' => 'Graduated Cylinder',
                'category_id' => 4,
                'beginning_qty' => 10,
                'current_qty' => 10,
                'laboratory' => 'Science Laboratory',
                'description' => 'A measuring tool used to measure the volume of liquids accurately.',
                'image' => 'dist/img/graduated_cylinder.jpg',
            ],
            [
                'item_name' => 'Glass Stirring Rod',
                'category_id' => 4,
                'beginning_qty' => 15,
                'current_qty' => 15,
                'laboratory' => 'Science Laboratory',
                'description' => 'A tool used for mixing chemicals and liquids in the laboratory.',
                'image' => 'dist/img/stirring_rod.jpg',
            ],
            [
                'item_name' => 'Hand Mixer',
                'category_id' => 1,
                'beginning_qty' => 4,
                'current_qty' => 4,
                'laboratory' => 'HM Laboratory',
                'description' => 'A handheld appliance used for mixing, beating, and whipping ingredients.',
                'image' => 'dist/img/hand_mixer.jpg',
            ],
        ]);
    }
}
