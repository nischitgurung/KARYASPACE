<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use Illuminate\Support\Facades\Auth;
use resources\views\dashboard;

class SpaceController extends Controller
{
    // Show list of all spaces owned by the logged-in user
    public function index() {
    $space = Auth::user()->spaces()->first(); // or any logic to choose space
    return view('dashboard', compact('space'));
}

    // Show form to create a new space
    public function create() {
        return view('spaces.create');
    }

    // Handle form submission to save a new space
    public function store(Request $request) {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create space associated with logged-in user
        $space = Auth::user()->spaces()->create($request->only('name', 'description'));

        // Redirect to projects creation or spaces listing
        return redirect()->route('spaces.index')->with('success', 'Space created successfully!');
    }
    public function show($id)
{
    $space = Space::with('projects')->findOrFail($id);
    return view('spaces.show', compact('space'));
}
public function destroy($id)
{
    $space = Space::findOrFail($id);
    $space->delete();

    return redirect()->route('spaces.index')->with('success', 'Space deleted successfully.');
}
public function edit($id)
{
    $space = Space::findOrFail($id);
    return view('spaces.edit', compact('space'));
}
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
    // Optionally, add methods like show, edit, update, destroy as needed
}
