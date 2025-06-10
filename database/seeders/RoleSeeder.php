<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; // Import Role model at the top


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


public function run()
{
    $roles = ['Admin', 'Project Manager', 'Member'];

    foreach ($roles as $role) {
        Role::firstOrCreate(['name' => $role]); // Only creates if not already in DB
    }
}

}
