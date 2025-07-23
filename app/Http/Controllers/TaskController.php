<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\Space;
use App\Models\Role;

class TaskController extends Controller
{
    public function create(Space $space, Project $project)
    {
        $this->authorizeRole($space, ['Admin', 'Project Manager']);
        return view('tasks.create', compact('space', 'project'));
    }

    public function store(Request $request, Space $space, Project $project)
    {
        $this->authorizeRole($space, ['Admin', 'Project Manager']);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date|after_or_equal:today',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $project->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        return redirect()->route('spaces.projects.tasks.index', [$space, $project])->with('success', 'Task created successfully.');
    }

    public function edit(Space $space, Project $project, Task $task)
    {
        $this->authorizeRole($space, ['Admin', 'Project Manager']);
        return view('tasks.edit', compact('space', 'project', 'task'));
    }

    public function update(Request $request, Space $space, Project $project, Task $task)
    {
        $this->authorizeRole($space, ['Admin', 'Project Manager']);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date|after_or_equal:today',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($request->only(['title', 'description', 'deadline', 'priority', 'status']));

        return redirect()->route('spaces.projects.tasks.index', [$space, $project])->with('success', 'Task updated successfully.');
    }

    public function destroy(Space $space, Project $project, Task $task)
    {
        $this->authorizeRole($space, ['Admin', 'Project Manager']);
        $task->delete();

        return redirect()->route('spaces.projects.tasks.index', [$space, $project])->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Space $space, Project $project, Task $task)
    {
        $user = $request->user();

        // Check user assigned to task
        if (!$task->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not assigned to this task.');
        }

        $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;
        $memberRoleId = Role::where('name', 'Member')->value('id');

        if (!$pivot || $pivot->role_id !== $memberRoleId) {
            abort(403, 'Only Members can update task progress.');
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->status = $request->status;
        $task->save();

        return redirect()->back()->with('success', 'Task status updated.');
    }

   private function authorizeRole(Space $space, array $allowedRoles)
{
    $user = auth()->user();

    // Always allow space creator
    if ($space->created_by === $user->id) {
        return;
    }

    // Always allow project manager for scoped routes
    $project = request()->route('project');
    if ($project && $project->project_manager_id === $user->id) {
        return;
    }

    // Check pivot role
    $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;
    if (!$pivot) abort(403, 'You are not a member of this space.');

    $allowedRoleIds = Role::whereIn('name', $allowedRoles)->pluck('id')->toArray();
    if (!in_array($pivot->role_id, $allowedRoleIds)) {
        abort(403, 'You do not have permission.');
    }

    
}
public function index(Space $space, Project $project)
{
    $this->authorizeRole($space, ['Admin', 'Project Manager', 'Member']);

    // Load tasks associated with the project
    $tasks = $project->tasks()->latest('deadline')->get();

    return view('tasks.index', compact('space', 'project', 'tasks'));
}


}
