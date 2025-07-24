<?php

namespace App\Http\Controllers;

use App\Models\{Space, Project, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        $project->load('tasks.users');
        return view('projects.index', compact('project'));
    }

    public function create(Space $space)
    {
        return view('projects.create', [
            'space' => $space,
            'spaceMembers' => $space->members
        ]);
    }

    public function store(Request $request, Space $space)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'date',
            'priority' => 'in:low,medium,high,urgent',
            'project_manager_id' => 'exists:users,id',
        ]);

        $space->projects()->create($validated);

        return redirect()
            ->route('spaces.show', $space)
            ->with('success', 'Project created successfully!');
    }

    public function edit(Space $space, Project $project)
    {
        $this->authorizeAccess($space, $project);

        return view('projects.edit', [
            'space' => $space,
            'project' => $project,
            'spaceMembers' => $space->members
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
            'project_manager_id' => 'nullable|exists:users,id',
        ]);

        $project->update($validated);

        return redirect()
            ->route('spaces.show', $space)
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Space $space, Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();

        return redirect()
            ->route('spaces.show', $space)
            ->with('success', 'Project deleted successfully.');
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

    /**
     * Shared access logic for Project Manager or Space Creator
     */
    protected function authorizeAccess(Space $space, Project $project): void
    {
        if (! in_array(Auth::id(), [$project->project_manager_id, $space->created_by])) {
            abort(403, 'Unauthorized');
        }
    }
}
