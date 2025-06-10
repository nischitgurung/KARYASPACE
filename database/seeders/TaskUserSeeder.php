<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;


class TaskUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
        public function run()
{
    Task::all()->each(function ($task) {
        $users = User::inRandomOrder()->take(rand(1, 3))->pluck('id'); // Assign 1-3 random users
        $task->users()->attach($users);
    });
}

    
}
