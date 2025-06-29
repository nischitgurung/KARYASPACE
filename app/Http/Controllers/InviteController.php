<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Space;
use App\Models\Invite;
use App\Models\Role;

class InviteController extends Controller
{
    public function generate(Request $request, Space $space)
    {
        $invite = Invite::create([
            'space_id' => $space->id,
            'token' => Str::uuid(),
            'inviter_id' => auth()->id(),
            'role_id' => Role::where('name', 'Member')->value('id'),
            'expires_at' => now()->addDays(7),
        ]);

        return back()->with('success', 'Invite link: ' . route('invite.accept', $invite->token));
    }

    public function accept($token)
    {
        $invite = Invite::where('token', $token)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        $invite->space->users()->syncWithoutDetaching([
            auth()->id() => ['role_id' => $invite->role_id ?? defaultRoleId()],
        ]);

        return redirect()->route('spaces.show', $invite->space)
                         ->with('success', 'You joined the space!');
    }

    public function showLink(Space $space)
    {
        // Try to fetch the most recent non-expired invite by this user
        $invite = Invite::where('space_id', $space->id)
            ->where('inviter_id', auth()->id())
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        // If none exists, create a new invite
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
}
