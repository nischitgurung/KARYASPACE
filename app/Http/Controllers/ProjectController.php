<?php

namespace App\Http\Controllers;
use App\Models\Space;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
// app/Http/Controllers/ProjectController.php

    public function create(Space $space)
    {
        return view('projects.create', compact('space'));
    }
public function store(Request $request, Space $space)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $space->projects()->create([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    return redirect()->route('spaces.index')->with('success', 'Project created successfully!');
}
public function edit(Space $space, Project $project)
{
    // Optional: check if the project belongs to the space to prevent URL tampering
    if ($project->space_id !== $space->id) {
        abort(404);
    }
    
    return view('projects.edit', compact('space', 'project'));
}
public function update(Request $request, Space $space, Project $project)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    if ($project->space_id !== $space->id) {
        abort(404);
    }

    $project->update([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    return redirect()->route('spaces.show', $space)->with('success', 'Project updated successfully!');
}
public function destroy(Space $space, Project $project)
{
    // Optional: confirm the project belongs to the space
    if ($project->space_id !== $space->id) {
        abort(404);
    }

    $project->delete();

    return redirect()->route('spaces.show', $space)->with('success', 'Project deleted successfully!');
}



}
