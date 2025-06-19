<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SpaceController extends Controller
{
    // Show list of all spaces owned by the logged-in user
    public function index()
    {
        $spaces = Auth::user()->spaces;  // get spaces of logged-in user
        return view('spaces.index', compact('spaces'));
    }

    // Show form to create a new space
    public function create()
    {
        return view('spaces.create');
    }

    // Handle form submission to save a new space
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create space associated with logged-in user
        $space = Auth::user()->spaces()->create($request->only('name', 'description'));

        // Redirect to spaces listing with success message
        return redirect()->route('spaces.index')->with('success', 'Space created successfully!');
    }

    // Show a single space with its projects and all users for assigning project manager
    public function show($id)
    {
        $space = Space::with('projects')->findOrFail($id);

        // Get all users to assign as project manager
        $users = User::all();

        return view('spaces.show', compact('space', 'users'));
    }

    // Show form to edit space details
    public function edit($id)
    {
        $space = Space::findOrFail($id);
        return view('spaces.edit', compact('space'));
    }

    // Update space details
    public function update(Request $request, Space $space)
    {
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

    // Delete a space
    public function destroy($id)
    {
        $space = Space::findOrFail($id);
        $space->delete();

        return redirect()->route('spaces.index')->with('success', 'Space deleted successfully.');
    }
    
}
