<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;

/**
 * Seeds the crops reference table with common agricultural crops.
 */
class CropSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        $crops = [
            ['name' => 'Wheat',  'scientific_name' => 'Triticum aestivum',    'category' => 'cereal',    'avg_growth_days' => 240, 'icon' => 'fa-solid fa-wheat-awn'],
            ['name' => 'Corn',   'scientific_name' => 'Zea mays',             'category' => 'cereal',    'avg_growth_days' => 110, 'icon' => 'fa-solid fa-seedling'],
            ['name' => 'Tomato', 'scientific_name' => 'Solanum lycopersicum', 'category' => 'vegetable', 'avg_growth_days' => 80,  'icon' => 'fa-solid fa-apple-whole'],
            ['name' => 'Potato', 'scientific_name' => 'Solanum tuberosum',    'category' => 'vegetable', 'avg_growth_days' => 100, 'icon' => 'fa-solid fa-carrot'],
            ['name' => 'Grapes', 'scientific_name' => 'Vitis vinifera',       'category' => 'fruit',     'avg_growth_days' => 180, 'icon' => 'fa-solid fa-wine-bottle'],
            ['name' => 'Barley', 'scientific_name' => 'Hordeum vulgare',      'category' => 'cereal',    'avg_growth_days' => 90,  'icon' => 'fa-solid fa-wheat-awn-circle-exclamation'],
        ];

        foreach ($crops as $crop) {
            Crop::firstOrCreate(['name' => $crop['name']], $crop);
        }
    }
}
