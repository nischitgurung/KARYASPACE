<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\User;





class ProjectUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    Project::all()->each(function ($project) {
        $users = User::inRandomOrder()->take(rand(2, 5))->pluck('id');
        foreach ($users as $user) {
            ProjectUser::create([
                'project_id' => $project->id,
                'user_id' => $user,
                'role' => Role::inRandomOrder()->first()->name,
            ]);
        }
    });
}

}
