<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProjectUser;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;

class ProjectUserFactory extends Factory
{
    protected $model = ProjectUser::class;

    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['Admin', 'Project Manager', 'Member']),

        ];
    }
}
