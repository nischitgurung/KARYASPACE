<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{Space, Project, Invite, Role};
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    // 🔗 Generate space invite link (defaults to Member)
    public function generate(Space $space)
    {
        $roleId = Role::where('name', 'Member')->value('id');

        $invite = Invite::create([
            'space_id' => $space->id,
            'token' => Str::uuid(),
            'inviter_id' => Auth::id(),
            'role_id' => $roleId,
            'expires_at' => now()->addDays(7),
        ]);

        return back()->with('success', 'Invite link: ' . route('invite.accept', $invite->token));
    }

    // 📥 Accept invite token
    public function accept($token)
    {
        $invite = Invite::where('token', $token)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$invite || !$invite->space) {
            return redirect()->route('spaces.index')->with('warning', 'This invite is invalid or has expired.');
        }

        $user = Auth::user();
        $space = $invite->space;

        if ($space->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('spaces.index')->with('info', 'You are already a member of this space.');
        }

        $space->users()->syncWithoutDetaching([
            $user->id => ['role_id' => $invite->role_id],
        ]);

        return redirect()->route('spaces.show', $space)->with('success', 'You joined the space!');
    }

    // 🔗 Show latest space invite link
    public function showLink(Space $space)
    {
        $roleId = Role::where('name', 'Member')->value('id');

        $invite = Invite::where('space_id', $space->id)
            ->where('inviter_id', Auth::id())
            ->where('role_id', $roleId)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->latest()->first();

        if (!$invite) {
            $invite = Invite::create([
                'space_id' => $space->id,
                'token' => Str::uuid(),
                'inviter_id' => Auth::id(),
                'role_id' => $roleId,
                'expires_at' => now()->addDays(7),
            ]);
        }

        return response()->json([
            'invite_link' => route('invite.accept', $invite->token),
        ]);
    }

    // ⚡ Accept manual invite URL
    public function handleJoin(Request $request)
    {
        $request->validate([
            'invite_link' => 'required|url',
        ]);

        $token = Str::afterLast($request->input('invite_link'), '/');

        return redirect()->route('invite.accept', ['token' => $token]);
    }

    // 🧩 Project-level invite link (useful if you add role_id/project_id context later)
    public function projectInviteLink(Space $space, Project $project)
    {
        if ($project->space_id !== $space->id) {
            abort(404, 'Invalid project.');
        }

        $roleId = Role::where('name', 'Member')->value('id');

        $invite = Invite::where('space_id', $space->id)
            ->where('inviter_id', Auth::id())
            ->where('role_id', $roleId)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        if (!$invite) {
            $invite = Invite::create([
                'space_id' => $space->id,
                'token' => Str::uuid(),
                'inviter_id' => Auth::id(),
                'role_id' => $roleId,
                'expires_at' => now()->addDays(7),
            ]);
        }

        return response()->json([
            'invite_link' => route('invite.accept', $invite->token),
        ]);
    }
}
