<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Space;

class CheckSpaceRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $space = $request->route('space');  // This should be a Space model
        $user = $request->user();

        // If it's an ID, convert to model
        if (!$space instanceof Space) {
            $space = Space::findOrFail($space);
        }

        $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;

        if (!$pivot) {
            abort(403, 'You are not a member of this space.');
        }

        // Normalize roles to lowercase for comparison
        $userRole = strtolower($pivot->role);
        $requiredRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $requiredRoles)) {
            abort(403, 'You do not have the required role to perform this action.');
        }

        return $next($request);
    }
}
