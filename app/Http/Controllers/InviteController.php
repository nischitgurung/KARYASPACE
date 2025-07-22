<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Space;
use App\Models\Invite;
use App\Models\Role;

class InviteController extends Controller
{
    // Generate an invite link for a space
   public function generate(Request $request, Space $space)
{
    $roleName = $request->input('role', 'Member');  // default to Member
    $roleId = Role::where('name', $roleName)->value('id');

    // Validate role existence
    if (!$roleId) {
        return back()->with('warning', 'Invalid role selected.');
    }

    $invite = Invite::create([
        'space_id' => $space->id,
        'token' => Str::uuid(),
        'inviter_id' => auth()->id(),
        'role_id' => $roleId,
        'expires_at' => now()->addDays(7),
    ]);

    return back()->with('success', 'Invite link: ' . route('invite.accept', $invite->token));
}


    // Accept an invitation link
    public function accept($token)
    {
        $invite = Invite::where('token', $token)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$invite || !$invite->space) {
            return redirect()->route('spaces.index')
                ->with('warning', 'This invite is invalid or the space no longer exists.');
        }

        $user = auth()->user();
        $space = $invite->space;

        // Prevent duplicate join
        if ($space->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('spaces.index')
                ->with('warning', 'You are already a member of this space.');
        }

        // Attach the user to the space with the role
        $space->users()->syncWithoutDetaching([
            $user->id => [
                'role_id' => $invite->role_id ?? Role::where('name', 'Member')->value('id')
            ],
        ]);

        return redirect()->route('spaces.show', $space)
            ->with('success', 'You joined the space!');
    }

    // Show latest valid invite link for a space or generate one if none exists
    public function showLink(Space $space)
    {
        $invite = Invite::where('space_id', $space->id)
            ->where('inviter_id', auth()->id())
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        if (!$invite) {
            $invite = Invite::create([
                'space_id' => $space->id,
                'token' => Str::uuid(),
                'inviter_id' => auth()->id(),
                'role_id' => Role::where('name', 'Member')->value('id'),
                'expires_at' => now()->addDays(7),
            ]);
        }

        return route('invite.accept', $invite->token);
    }

    // Handle manual invite link submission
    public function handleJoin(Request $request)
    {
        $request->validate([
            'invite_link' => 'required|url'
        ]);

        $token = Str::afterLast($request->input('invite_link'), '/');
        return redirect()->route('invite.accept', ['token' => $token]);
    }
}
