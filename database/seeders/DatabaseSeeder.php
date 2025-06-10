<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
{
    $this->call([
        RoleSeeder::class,
        UserSeeder::class, // Users first
        SpaceSeeder::class,
        ProjectSeeder::class,
        TaskSeeder::class,
        CommentSeeder::class,
        SpaceUserSeeder::class,
        ProjectUserSeeder::class,
        TaskUserSeeder::class,
        UserSeeder::class,
        ProgressSeeder::class,
    ]);
}

}
