<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpaceUserController extends Controller
{
    /**
     * Display a listing of members in the space.
     * Accessible to all members of the space.
     */
  public function index(Space $space)
{
    $members = $space->users()->withPivot('role_id')->get();

    $roleIds = $members->pluck('pivot.role_id')->unique()->toArray();
    $roles = Role::whereIn('id', $roleIds)->pluck('name', 'id');

    $members->each(function ($member) use ($roles) {
        $member->role_name = $roles->get($member->pivot->role_id) ?? 'Unknown';
    });

    $userId = auth()->id();
    $pivot = $space->users()->where('user_id', $userId)->first()?->pivot;
    $adminRoleId = Role::where('name', 'Admin')->value('id');
    $pmRoleId = Role::where('name', 'Project Manager')->value('id'); // <-- Add this

    $canManage = $space->created_by === $userId || ($pivot && $pivot->role_id === $adminRoleId);

    return view('spaces.members.index', compact('space', 'members', 'canManage', 'adminRoleId', 'pmRoleId'));
}


    /**
     * Show the form for editing the specified member's role.
     * Only accessible by Admin or Space Creator.
     */
    public function edit(Space $space, User $user)
    {
        $this->authorizeAdminOrCreator($space);

        $roles = Role::all();

        return view('spaces.members.edit', compact('space', 'user', 'roles'));
    }

    /**
     * Update the specified member's role in the space.
     * Only accessible by Admin or Space Creator.
     */
    public function update(Request $request, Space $space, User $user)
    {
        $this->authorizeAdminOrCreator($space);

        // Prevent users from changing their own role
        if ($user->id === Auth::id()) {
            return back()->with('warning', 'You cannot change your own role.');
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $space->users()->updateExistingPivot($user->id, [
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('spaces.members.index', $space)
                         ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified member from the space.
     * Only accessible by Admin or Space Creator.
     */
    public function destroy(Space $space, User $user)
    {
        $this->authorizeAdminOrCreator($space);

        // Prevent removing self or space owner
        if ($user->id === Auth::id()) {
            return back()->with('warning', 'You cannot remove yourself from the space.');
        }

        if ($user->id === $space->created_by) {
            return back()->with('warning', 'You cannot remove the space owner.');
        }

        $space->users()->detach($user->id);

        return redirect()->route('spaces.members.index', $space)
                         ->with('success', 'Member removed successfully.');
    }

    /**
     * Helper method to authorize that current user
     * is either Space Creator, Admin, or Project Manager.
     * Throws 403 if unauthorized.
     */
    private function authorizeAdminOrCreator(Space $space)
    {
        $userId = Auth::id();

        // Get pivot record for current user in this space
        $pivot = $space->users()->where('user_id', $userId)->first()?->pivot;

        // Fetch Admin and Project Manager role IDs
        $adminRoleId = Role::where('name', 'Admin')->value('id');
        $pmRoleId = Role::where('name', 'Project Manager')->value('id');

        // Allow if user is creator, or has Admin or Project Manager role
        if (
            $space->created_by !== $userId &&
            (!$pivot || !in_array($pivot->role_id, [$adminRoleId, $pmRoleId]))
        ) {
            abort(403, 'Only Admins, Project Managers, or the Space Creator can manage members.');
        }
    }
}
