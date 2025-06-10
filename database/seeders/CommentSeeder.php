<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;




class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Task::all()->each(function ($task) {
        $users = User::inRandomOrder()->take(rand(2, 4))->pluck('id');
        foreach ($users as $user) {
            Comment::create([
                'content' => fake()->paragraph(),
                'user_id' => $user,
                'task_id' => $task->id,
            ]);
        }
    });
}

}
