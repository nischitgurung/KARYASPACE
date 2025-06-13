<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Space;
use App\Models\Project;
use App\Models\Task;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_project()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($admin)
            ->post('/projects', ['name' => 'New Project'])
            ->assertStatus(201);
    }

    public function test_project_manager_can_create_task()
    {
        $manager = User::factory()->create(['role' => 'Project Manager']);
        $project = Project::factory()->create();

        $this->actingAs($manager)
            ->post("/projects/{$project->id}/tasks", ['name' => 'New Task'])
            ->assertStatus(201);
    }

    public function test_member_cannot_create_task()
    {
        $member = User::factory()->create(['role' => 'Member']);
        $project = Project::factory()->create();

        $this->actingAs($member)
            ->post("/projects/{$project->id}/tasks", ['name' => 'Unauthorized Task'])
            ->assertStatus(403);
    }
}
