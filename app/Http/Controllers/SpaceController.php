<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SpaceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $spaces = Space::where('user_id', $user->id)
            ->orWhereHas('users', fn ($q) => $q->where('user_id', $user->id))
            ->withCount('projects')
            ->get();

        return view('spaces.index', compact('spaces'));
    }

    public function create()
    {
        return view('spaces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $space = Auth::user()->spaces()->create($request->only('name', 'description'));

        return redirect()->route('spaces.index')->with('success', 'Space created successfully!');
    }

    public function show($id)
    {
        $space = Space::with('projects')->findOrFail($id);
        $users = User::all();

        return view('projects.index', compact('space', 'users'));
    }

    public function edit($id)
    {
       $space = Space::findOrFail($id);
if ((int) $space->user_id !== (int) auth()->id()) {
    return redirect()->route('spaces.index')->with('warning', 'You do not have permission to edit this space.');
}

        

        return view('spaces.edit', compact('space'));
    }

    public function update(Request $request, $id)
{
    $space = Space::findOrFail($id);

    if ((int) $space->user_id !== (int) auth()->id()) {
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

    public function destroy($id)
    {
        $space = Space::findOrFail($id);

        // ⛔ Only allow creator/admin to delete
        if ($space->user_id !== Auth::id()) {
            return redirect()->route('spaces.index')->with('warning', 'You do not have permission to delete this space.');
        }

        $space->delete();

        return redirect()->route('spaces.index')->with('success', 'Space deleted successfully.');
    }

    public function leave(Space $space)
    {
        $space->users()->detach(Auth::id());
        return redirect()->route('spaces.index')->with('success', 'You left the space.');
    }
}
