<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    return [
        'name' => $this->faker->sentence(),
        'description' => $this->faker->paragraph(),
        'space_id' => Space::factory(), // Creates a related space
        'user_id' => User::factory(), // Assigns a random user as Project Manager
        'completion_percentage' => $this->faker->numberBetween(0, 100),
    ];
}

}
