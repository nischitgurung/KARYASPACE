<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Space;
use App\Models\SpaceUser;
use App\Models\Role;
use App\Models\User;



class SpaceUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    Space::all()->each(function ($space) {
        $users = User::inRandomOrder()->take(rand(2, 5))->pluck('id'); // Assign 2-5 users
        foreach ($users as $user) {
            SpaceUser::create([
                'space_id' => $space->id,
                'user_id' => $user,
                'role' => Role::inRandomOrder()->first()->name, // Assign random role
            ]);
        }
    });
}

}
