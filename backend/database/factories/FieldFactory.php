<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    protected $model = Field::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'farm_id'   => Farm::factory(),
            'name'      => ucfirst(fake()->word()) . ' Field',
            'area_ha'   => fake()->randomFloat(2, 0.5, 50),
            'soil_type' => fake()->randomElement(['clay', 'sandy', 'loam', 'silty']),
            'irrigated' => fake()->boolean(),
        ];
    }
}
