<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects within a specific space.
     */
    public function index(Space $space)
    {
        // Fetch all projects belonging to the given space
        $projects = $space->projects()->with('manager')->get(); // Eager load the manager

        // Return a view to display the projects list
        return view('projects.index', compact('space', 'projects'));
    }

    /**
     * Show the form for creating a new project within a space.
     */
    public function create(Space $space)
    {
        // Get members of the space to populate the project manager dropdown
        $spaceMembers = $space->members;
        return view('projects.create', compact('space', 'spaceMembers'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request, Space $space)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'project_manager_id' => 'required|exists:users,id', // Ensure the manager exists
        ]);

        $space->projects()->create($validated);

        return redirect()->route('spaces.show', $space)->with('success', 'Project created successfully!');
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Space $space, Project $project)
    {
        // The authorization logic is now handled by the policy.
        // If the route is scoped correctly, the check that the project
        // belongs to the space is handled automatically by Laravel.
        $this->authorize('update', $project);

        $spaceMembers = $space->members;
        return view('projects.edit', compact('space', 'project', 'spaceMembers'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Space $space, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'project_manager_id' => 'nullable|exists:users,id',
        ]);

        $project->update($validated);

        return redirect()->route('spaces.show', $space)->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Space $space, Project $project)
    {
        $this->authorize('delete', $project);
        
        $project->delete();

        return redirect()->route('spaces.show', $space)->with('success', 'Project deleted successfully!');
    }

    /**
     * Add an employee to the project's team.
     */
    public function addEmployee(Request $request, Space $space, Project $project)
    {
        $this->authorize('manageMembers', $project);

        $request->validate(['user_id' => 'required|exists:users,id']);

        // Use syncWithoutDetaching to add the user without affecting others.
        // This also prevents duplicate entries automatically.
        $project->employees()->syncWithoutDetaching($request->user_id);

        return back()->with('success', 'Employee added to project.');
    }

    /**
     * Remove an employee from the project's team.
     */
    public function removeEmployee(Space $space, Project $project, User $user)
    {
        $this->authorize('manageMembers', $project);
        
        $project->employees()->detach($user->id);

        return back()->with('success', 'Employee removed from project.');
    }
}