<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\Farm;
use App\Models\Field;
use App\Models\Plantation;
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

        $farm = $user->farms()->firstOrCreate(
            ['name' => 'Green Valley Farm'],
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
            $farm->fields()->firstOrCreate(['name' => $def['name']], $def);
        }

        // Reload fields keyed by name for plantation seeding
        $fields = $farm->fields()->get()->keyBy('name');
        $crops  = Crop::all()->keyBy('name');

        $plantationDefs = [
            [
                'field'              => 'Field A — North Ridge',
                'crop'               => 'Wheat',
                'planted_at'         => '2026-03-10',
                'expected_harvest_at'=> '2026-07-15',
                'area_ha'            => 18.5,
                'notes'              => 'Winter wheat variety. Second season.',
            ],
            [
                'field'              => 'Field B — South Slope',
                'crop'               => 'Tomato',
                'planted_at'         => '2026-04-01',
                'expected_harvest_at'=> '2026-07-20',
                'area_ha'            => 12.0,
                'notes'              => 'Cherry tomato greenhouse extension.',
            ],
            [
                'field'              => 'Field C — Riverside',
                'crop'               => 'Corn',
                'planted_at'         => '2026-05-05',
                'expected_harvest_at'=> '2026-09-10',
                'area_ha'            => 9.2,
                'notes'              => null,
            ],
            [
                'field'              => 'Field D — East Plateau',
                'crop'               => 'Wheat',
                'planted_at'         => '2026-02-20',
                'expected_harvest_at'=> '2026-06-30',
                'harvested_at'       => '2026-06-28',
                'yield_kg'           => 320.0,
                'area_ha'            => 15.8,
                'notes'              => 'Excellent yield this season.',
            ],
        ];

        foreach ($plantationDefs as $def) {
            $field = $fields->get($def['field']);
            $crop  = $crops->get($def['crop']);
            if (! $field || ! $crop) {
                continue;
            }
            $field->plantations()->firstOrCreate(
                ['crop_id' => $crop->id, 'planted_at' => $def['planted_at']],
                [
                    'expected_harvest_at' => $def['expected_harvest_at'] ?? null,
                    'harvested_at'        => $def['harvested_at'] ?? null,
                    'yield_kg'            => $def['yield_kg'] ?? null,
                    'area_ha'             => $def['area_ha'] ?? null,
                    'notes'               => $def['notes'] ?? null,
                ]
            );
        }
    }
}
