<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    return [
        'content' => $this->faker->paragraph(), // Generates a random comment text
        'user_id' => User::factory(), // Links comment to a random user
        'task_id' => Task::factory(), // Associates comment with a task
    ];
}

}
