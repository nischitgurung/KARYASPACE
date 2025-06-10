<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;



class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    Project::all()->each(function ($project) {
        Task::factory()->count(rand(3, 7))->create(['project_id' => $project->id]);
    });
}


}
