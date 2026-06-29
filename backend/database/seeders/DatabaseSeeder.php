<?php

namespace Database\Seeders;

use App\Models\Farm;
use App\Models\Field;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Primary database seeder.
 * Seeds crop reference data and creates a demo user with a farm and fields.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reference data
        $this->call(CropSeeder::class);

        // Demo account — demo@wisefarm.com / password
        $user = User::firstOrCreate(
            ['email' => 'demo@wisefarm.com'],
            [
                'name'     => 'Amélie Rousseau',
                'password' => Hash::make('password'),
            ]
        );

        $farm = Farm::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Green Valley Farm'],
            [
                'location'      => 'Provence, France',
                'total_area_ha' => 100.00,
            ]
        );

        $fieldDefs = [
            ['name' => 'Field A — North Ridge',  'area_ha' => 18.5, 'soil_type' => 'loam',  'irrigated' => true],
            ['name' => 'Field B — South Slope',  'area_ha' => 24.0, 'soil_type' => 'clay',  'irrigated' => false],
            ['name' => 'Field C — Riverside',    'area_ha' => 9.2,  'soil_type' => 'sandy', 'irrigated' => true],
            ['name' => 'Field D — East Plateau', 'area_ha' => 15.8, 'soil_type' => 'silt',  'irrigated' => false],
            ['name' => 'Field E — Hillside',     'area_ha' => 12.4, 'soil_type' => 'loam',  'irrigated' => false],
            ['name' => 'Field F — Lower Meadow', 'area_ha' => 20.1, 'soil_type' => 'clay',  'irrigated' => true],
        ];

        foreach ($fieldDefs as $def) {
            Field::firstOrCreate(
                ['farm_id' => $farm->id, 'name' => $def['name']],
                $def
            );
        }
    }
}
