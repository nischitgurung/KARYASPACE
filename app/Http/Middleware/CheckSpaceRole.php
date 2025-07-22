<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;

class CheckSpaceRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $space = $request->route('space'); // Now 'space' param always exists in routes
        $user = $request->user();

        if (!$space) {
            abort(403, 'Space not found.');
        }

        $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;

        if (!$pivot) {
            abort(403, 'You are not a member of this space.');
        }

        $userRoleId = $pivot->role_id;
        $allowedRoleIds = Role::whereIn('name', $roles)->pluck('id')->toArray();

        if (!in_array($userRoleId, $allowedRoleIds)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
