<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProjectUser;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskUser;
class ProjectUserFactory extends Factory
{
    protected $model = ProjectUser::class;

   public function getVisibleProjects(User $user)
{
    return Project::where('space_id', $user->spaces->pluck('id'))->get(); // Visibility
}

public function getEditableProjects(User $user)
{
    return ProjectUser::where('user_id', $user->id)->pluck('project_id'); // Assigned projects
}

public function getVisibleTasks(User $user)
{
    return Task::whereIn('project_id', ProjectUser::where('user_id', $user->id)->pluck('project_id'))->get();
}

public function getEditableTasks(User $user)
{
    return TaskUser::where('user_id', $user->id)->pluck('task_id'); // Assigned tasks
}

// Assigned projects


    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['Admin', 'Project Manager', 'Member']),

        ];
    }
}
