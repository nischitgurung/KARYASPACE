<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpaceUserController extends Controller
{
    // Show all members of the space with their roles - Admin only
    public function index(Space $space)
    {
        $this->authorizeAdmin($space);

        // Get users of the space with pivot role_id
        $members = $space->users()->withPivot('role_id')->get();

        // Map each member to add role name from Role model for display convenience
        $roleIds = $members->pluck('pivot.role_id')->unique()->toArray();
        $roles = Role::whereIn('id', $roleIds)->pluck('name', 'id');

        // Attach role name to each member model dynamically (not saved in DB)
        $members->each(function ($member) use ($roles) {
            $member->role_name = $roles->get($member->pivot->role_id) ?? 'Unknown';
        });

        return view('spaces.members.index', compact('space', 'members'));
    }

    // Show form to edit a member's role - Admin only
    public function edit(Space $space, User $user)
    {
        $this->authorizeAdmin($space);

        $roles = Role::all();

        return view('spaces.members.edit', compact('space', 'user', 'roles'));
    }

    // Update a member's role - Admin only
    public function update(Request $request, Space $space, User $user)
    {
        $this->authorizeAdmin($space);

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $space->users()->updateExistingPivot($user->id, [
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('spaces.members.index', $space)
                         ->with('success', 'Role updated successfully.');
    }

    // Remove a member from the space - Admin only
    public function destroy(Space $space, User $user)
    {
        $this->authorizeAdmin($space);

        $space->users()->detach($user->id);

        return redirect()->route('spaces.members.index', $space)
                         ->with('success', 'Member removed successfully.');
    }

    // Helper method to check if the current user is an Admin in this space
    private function authorizeAdmin(Space $space)
    {
        $userId = Auth::id();

        // Get the pivot row for current user in this space
        $pivot = $space->users()->where('user_id', $userId)->first()?->pivot;

        // Get Admin role id once (cache if necessary)
        $adminRoleId = Role::where('name', 'Admin')->value('id');

        if (!$pivot || $pivot->role_id !== $adminRoleId) {
            abort(403, 'Only Admins can manage members.');
        }
    }
}
