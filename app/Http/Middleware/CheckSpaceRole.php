<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;

class CheckSpaceRole
{
   public function handle(Request $request, Closure $next, ...$roles)
{
    $space = $request->route('space');
    $user = $request->user();

    if (!$space) {
        abort(403, 'Space not found.');
    }

    // Allow space creator automatically
    if ($space->created_by === $user->id) {
        return $next($request);
    }

    // Check pivot role
    $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;

    if (!$pivot) {
        abort(403, 'You are not a member of this space.');
    }

    $userRoleId = $pivot->role_id;
    $allowedRoleIds = Role::whereIn('name', $roles)->pluck('id')->toArray();

    if (in_array($userRoleId, $allowedRoleIds)) {
        return $next($request);
    }

    // Check project manager role if project is part of the route
    $project = $request->route('project');
    if ($project && $project->project_manager_id === $user->id) {
        return $next($request);
    }

    abort(403, 'You do not have permission to access this resource.');
}

}
