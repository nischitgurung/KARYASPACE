<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Space;
use App\Models\User;


class SpaceFactory extends Factory
{
    protected $model = Space::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'user_id' => User::factory(), // Assigns a random user as space owner
        ];
    }
}
