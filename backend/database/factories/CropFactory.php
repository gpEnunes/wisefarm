<?php

namespace Database\Factories;

use App\Models\Crop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Crop>
 */
class CropFactory extends Factory
{
    protected $model = Crop::class;

    public function definition(): array
    {
        return [
            'name'            => fake()->unique()->word(),
            'scientific_name' => fake()->optional()->words(2, true),
            'category'        => fake()->randomElement(['cereal', 'vegetable', 'fruit', 'other']),
            'avg_growth_days' => fake()->numberBetween(60, 300),
            'icon'            => 'fa-solid fa-seedling',
        ];
    }
}
