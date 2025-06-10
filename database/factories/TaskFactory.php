<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    return [
        'title' => $this->faker->sentence(),
        'description' => $this->faker->paragraph(),
        'status' => $this->faker->randomElement(['Pending', 'In Progress', 'Completed']),
        'priority' => $this->faker->randomElement(['Low', 'Medium', 'High']),
        'due_date' => $this->faker->date(),
        'project_id' => Project::factory(), // Auto-generates a related project
    ];
}

}
