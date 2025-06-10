<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SpaceUser;
use App\Models\Space;
use App\Models\User;
use App\Models\Role;

class SpaceUserFactory extends Factory
{
    protected $model = SpaceUser::class;
   

    public function definition()
    {
        return [
            'space_id' => Space::factory(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['Admin', 'Project Manager', 'Member']),

        ];
    }
}
