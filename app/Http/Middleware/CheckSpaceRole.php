<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSpaceRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $space = $request->route('space');
        $user = $request->user();
        $userRole = $user->getRoleInSpace($space->id);

        if (!in_array($userRole, $roles)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
