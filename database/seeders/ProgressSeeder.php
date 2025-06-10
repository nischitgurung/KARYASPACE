<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Progress;



class ProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Project::all()->each(function ($project) {
        Progress::create([
            'project_id' => $project->id,
            'completion_percentage' => fake()->numberBetween(0, 100),
        ]);
    });
}

}
