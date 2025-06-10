<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TaskUser;
use App\Models\Task;
use App\Models\User;

class TaskUserFactory extends Factory
{
    protected $model = TaskUser::class;

    public function definition()
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
        ];
    }
}
