<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Space;
use App\Models\Invite;
use App\Models\Role;

class InviteController extends Controller
{
    // 🔗 Generate invite link for a space (hardcoded to Member role)
    public function generate(Space $space)
    {
        $roleId = Role::where('name', 'Member')->value('id');

        $invite = Invite::create([
            'space_id' => $space->id,
            'token' => Str::uuid(),
            'inviter_id' => auth()->id(),
            'role_id' => $roleId,
            'expires_at' => now()->addDays(7),
        ]);

        return back()->with('success', 'Invite link: ' . route('invite.accept', $invite->token));
    }

    // 📥 Accept invite token and attach user to space
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

        if ($space->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('spaces.index')
                ->with('warning', 'You are already a member of this space.');
        }

        $space->users()->syncWithoutDetaching([
            $user->id => ['role_id' => $invite->role_id],
        ]);

        return redirect()->route('spaces.show', $space)
            ->with('success', 'You joined the space as a Member!');
    }

    // ⚡ Return JSON invite link (used by AJAX, no role selection)
    public function showLink(Space $space)
    {
        $roleId = Role::where('name', 'Member')->value('id');

        $invite = Invite::where('space_id', $space->id)
            ->where('inviter_id', auth()->id())
            ->where('role_id', $roleId)
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
                'role_id' => $roleId,
                'expires_at' => now()->addDays(7),
            ]);
        }

        return response()->json([
            'invite_link' => route('invite.accept', $invite->token)
        ]);
    }

    // 🔗 Manual link join fallback
    public function handleJoin(Request $request)
    {
        $request->validate([
            'invite_link' => 'required|url',
        ]);

        $token = Str::afterLast($request->input('invite_link'), '/');

        return redirect()->route('invite.accept', ['token' => $token]);
    }
}
