<?php
// app/Policies/ProjectPolicy.php


namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use App\Models\Space; // Assuming Space model is involved in authorization
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */
public function update(User $user, Project $project): bool
{
    return true;
}
public function delete(User $user, Project $project): bool
{
    return true; // temporarily allow all to test
}


    // ... other policy methods (view, create, delete, etc.)
}