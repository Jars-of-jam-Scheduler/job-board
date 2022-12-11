<?php

namespace Database\Factories;

use App\Models\{Role, User};

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppModelsJob>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
			'title' => fake()->jobTitle(),
			'firm_id' => User::factory()->hasAttached([Role::findOrFail('firm')]),
			'presentation' => fake()->paragraph(),
			'min_salary' => fake()->numberBetween(1000, 10000),
			'max_salary' => fake()->numberBetween(10000, 100000),
			'working_place' => fake()->randomElement(['full_remote', 'hybrid_remote', 'no_remote']),
			'working_place_country' => fake()->randomElement(['fr']),
			'employment_contract_type' => fake()->randomElement(['cdi', 'cdd']),
			'contractual_working_time' => fake()->sentence(),
			'collective_agreement' => fake()->randomElement(['syntec']),
			'flexible_hours' => fake()->boolean(),
			'working_hours_modulation_system' => fake()->boolean(),
        ];
    }
}