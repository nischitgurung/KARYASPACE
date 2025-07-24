<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Space; // Make sure your Space model is imported

class SpaceRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  The roles to check for (e.g., 'Admin', 'Member')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Get the authenticated user and the space from the route
        $user = $request->user();
        $space = $request->route('space');

        // Deny access if user is not logged in or space doesn't exist
        if (!$user || !$space instanceof Space) {
            abort(403, 'Unauthorized Action.');
        }

        // Check if the user has the required role in this space.
        // This relies on the hasSpaceRole() method in your User model.
        if (!$user->hasSpaceRole($space, $roles)) {
            abort(403, 'You do not have the required role to perform this action.');
        }

        // If all checks pass, allow the request to continue.
        return $next($request);
    }
}