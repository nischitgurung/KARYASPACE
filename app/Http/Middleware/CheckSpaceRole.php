<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\Project;

class CheckSpaceRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        dd('✅ Middleware found and running');

        
        $user = $request->user();

        // 🔍 Trace route parameters
        $space = $request->route('space');
        $project = $request->route('project');

        // Resolve space from route or project
        if (!$space instanceof Space) {
            $space = is_numeric($space) ? Space::find($space) : null;
        }

        if (!$space && $project instanceof Project) {
            $space = $project->space;
        }

        if (!$space) {
            logger('🧯 Space not resolved at all');
            abort(403, 'Missing space context.');
        }

        // ✅ Creator bypass
        if ($space->created_by === $user->id) {
            logger('✅ Creator override granted', ['user_id' => $user->id, 'space_id' => $space->id]);
            return $next($request);
        }

        // 🔐 Role check via pivot
        $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;

        if (!$pivot) {
            logger('❌ No pivot found: user not member', ['user_id' => $user->id, 'space_id' => $space->id]);
            abort(403, 'You are not a member of this space.');
        }

        $userRole = strtolower($pivot->role ?? '');
        $requiredRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $requiredRoles)) {
            logger('⛔ Role mismatch', ['user_role' => $userRole, 'required_roles' => $requiredRoles]);
            abort(403, 'You do not have the required role to perform this action.');
        }

        logger('✅ Role verified', ['user_id' => $user->id, 'space_id' => $space->id, 'role' => $userRole]);
        return $next($request);
    }
}
