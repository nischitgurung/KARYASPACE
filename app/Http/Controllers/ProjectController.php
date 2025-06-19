<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Show form to create a new project inside a space
    public function create(Space $space)
    {
        return view('projects.create', compact('space'));
    }

    // Store new project with deadline and priority
    public function store(Request $request, Space $space)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $space->projects()->create([
            'name' => $request->name,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'project_manager_id' => Auth::id(),
        ]);

        return redirect()->route('spaces.projects.index', $space)->with('success', 'Project created successfully!');
    }

    // Show edit form for a project within a space
    public function edit(Space $space, Project $project)
    {
        if ($project->space_id !== $space->id) {
            abort(404);
        }

        return view('projects.edit', compact('space', 'project'));
    }

    // Update project details including deadline and priority
    public function update(Request $request, Space $space, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        if ($project->space_id !== $space->id) {
            abort(404);
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
        ]);

        return redirect()->route('spaces.show', $space)->with('success', 'Project updated successfully!');
    }

    // Delete a project
    public function destroy(Space $space, Project $project)
    {
        if ($project->space_id !== $space->id) {
            abort(404);
        }

        $project->delete();

        return redirect()->route('spaces.show', $space)->with('success', 'Project deleted successfully!');
    }

    // Add an employee to the project (only project manager allowed)
    public function addEmployee(Space $space, Project $project, Request $request)
    {
        if ($project->space_id !== $space->id) {
            abort(404);
        }

        if ($project->project_manager_id !== Auth::id()) {
            abort(403, 'Unauthorized: Only the project manager can add employees.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Attach employee if not already attached
        if (!$project->employees()->where('user_id', $request->user_id)->exists()) {
            $project->employees()->attach($request->user_id);
        }

        return back()->with('success', 'Employee added to project.');
    }

    // Remove an employee from the project (only project manager allowed)
    public function removeEmployee(Space $space, Project $project, User $user)
    {
        if ($project->space_id !== $space->id) {
            abort(404);
        }

        if ($project->project_manager_id !== Auth::id()) {
            abort(403, 'Unauthorized: Only the project manager can remove employees.');
        }

        $project->employees()->detach($user->id);

        return back()->with('success', 'Employee removed from project.');
    }
    public function index(Space $space)
{
    // Fetch all projects belonging to the given space
    $projects = $space->projects()->get();

    // Return a view to display the projects list
    return view('projects.index', compact('space', 'projects'));
}

public function assignManager(Request $request, $projectId)
{
    $request->validate([
        'project_manager_id' => 'nullable|exists:users,id',
    ]);

    $project = Project::findOrFail($projectId);
    $project->project_manager_id = $request->input('project_manager_id');
    $project->save();

    return redirect()->back()->with('success', 'Project manager assigned successfully.');
}


}
