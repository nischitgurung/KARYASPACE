<?php

namespace App\Http\Controllers;

use App\Models\{Space, Project, User};
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Space $space)
    {
        $projects = $space->projects()->with('projectManager')->get();
        return view('projects.index', compact('space', 'projects'));
    }

    public function show(Project $project)
    {
        $project->load('tasks.users');
        return view('projects.index', compact('project'));
    }

    public function create(Space $space)
    {
        if (! $this->isAdmin($space)) {
            abort(403, 'Only Admins can create projects.');
        }

        return view('projects.create', [
            'space' => $space,
            'spaceMembers' => $space->members ?? collect(), // ✅ fallback prevents null error
        ]);
    }

    public function store(Request $request, Space $space)
    {
        if (! $this->isAdmin($space)) {
            abort(403, 'Only Admins can store projects.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'date',
            'priority' => 'in:low,medium,high,urgent',
            'project_manager_id' => 'nullable|exists:users,id', // ✅ now optional
        ]);

        $space->projects()->create($validated);

        return redirect()->route('spaces.show', $space)->with('success', 'Project created successfully!');
    }

    public function edit(Space $space, Project $project)
    {
        $this->authorizeAccess($space, $project);

        return view('projects.edit', [
            'space' => $space,
            'project' => $project,
            'spaceMembers' => $space->members ?? collect(), // ✅ safety fallback
        ]);
    }

    public function update(Request $request, Space $space, Project $project)
    {
        $this->authorizeAccess($space, $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'project_manager_id' => 'nullable|exists:users,id', // ✅ optional
        ]);

        $project->update($validated);

        return redirect()->route('spaces.show', $space)->with('success', 'Project updated successfully!');
    }

    public function destroy(Space $space, Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();

        return redirect()->route('spaces.show', $space)->with('success', 'Project deleted successfully.');
    }

    public function addEmployee(Request $request, Space $space, Project $project)
    {
        $this->authorizeAccess($space, $project);

        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $project->employees()->syncWithoutDetaching($request->user_id);

        return back()->with('success', 'Employee added to project.');
    }

    public function removeEmployee(Space $space, Project $project, User $user)
    {
        $this->authorizeAccess($space, $project);

        $project->employees()->detach($user->id);

        return back()->with('success', 'Employee removed from project.');
    }

    protected function authorizeAccess(Space $space, Project $project): void
    {
        $user = auth()->user();

        if (! $user) {
            abort(403, 'Unauthorized');
        }

        if ($user->id === $project->project_manager_id || $this->isAdmin($space)) {
            return;
        }

        abort(403, 'Access denied. Only Project Managers or Admins can perform this action.');
    }

    protected function isAdmin(Space $space): bool
    {
        $user = auth()->user();

        return $space->users()
            ->where('user_id', $user->id)
            ->where('space_user.role_id', 1) // ✅ assumes role ID 1 = Admin
            ->exists();
    }
}
