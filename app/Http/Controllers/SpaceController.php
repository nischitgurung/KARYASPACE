<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpaceController extends Controller
{
    /**
     * Display a listing of spaces where user is creator or member.
     */
    public function index()
    {
        $userId = Auth::id();

        $spaces = Space::where('user_id', $userId)
            ->orWhereHas('users', fn ($q) => $q->where('user_id', $userId))
            ->withCount('projects')
            ->get();

        return view('spaces.index', compact('spaces'));
    }

    /**
     * Show form to create new space.
     */
    public function create()
    {
        return view('spaces.create');
    }

    /**
     * Store a new space with authenticated user as creator and admin member.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $userId = Auth::id();

            // Create the space
            $space = new Space();
            $space->name = $request->name;
            $space->description = $request->description;
            $space->user_id = $userId; // Creator
            $space->save();

            // Add creator as member with admin role
            $space->users()->attach($userId, ['role_id' => 1]); // Adjust role_id if needed
        });

        return redirect()->route('spaces.index')->with('success', 'Space created and you’ve been added as admin.');
    }

    /**
     * Display the specified space only if user is creator or member.
     */
    public function show($id)
    {
        $space = Space::with('projects')->findOrFail($id);
        $userId = Auth::id();

        if ($space->user_id !== $userId && !$space->users()->where('user_id', $userId)->exists()) {
            abort(403, 'You are not authorized to view this space.');
        }

        $users = $space->users()->withPivot('role_id')->get();

        return view('projects.index', compact('space', 'users'));
    }

    /**
     * Show form to edit space only if user is creator.
     */
    public function edit($id)
    {
        $space = Space::findOrFail($id);
        if ((int) $space->user_id !== (int) Auth::id()) {
            return redirect()->route('spaces.index')->with('warning', 'You do not have permission to edit this space.');
        }

        return view('spaces.edit', compact('space'));
    }

    /**
     * Update space only if user is creator.
     */
    public function update(Request $request, $id)
    {
        $space = Space::findOrFail($id);
        if ((int) $space->user_id !== (int) Auth::id()) {
            return redirect()->route('spaces.index')->with('warning', 'You do not have permission to update this space.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $space->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('spaces.index')->with('success', 'Space updated successfully!');
    }

    /**
     * Delete space only if user is creator.
     */
    public function destroy($id)
    {
        $space = Space::findOrFail($id);
        if ((int) $space->user_id !== (int) Auth::id()) {
            return redirect()->route('spaces.index')->with('warning', 'You do not have permission to delete this space.');
        }

        $space->delete();

        return redirect()->route('spaces.index')->with('success', 'Space deleted successfully.');
    }

    /**
     * Allow a user to leave a space.
     */
    public function leave(Space $space)
    {
        $userId = Auth::id();

        $space->users()->detach($userId);

        return redirect()->route('spaces.index')->with('success', 'You left the space.');
    }
}
