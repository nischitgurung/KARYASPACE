<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\Space;
use App\Models\Role;
use Illuminate\Support\Facades\Auth; // Import Auth facade for clarity

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks under a project in a space.
     *
     * @param  \App\Models\Space  $space
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function index(Space $space, Project $project)
    {
        // Ensure the project belongs to the space to prevent URL manipulation
        if ($project->space_id !== $space->id) {
            abort(404, 'Project does not belong to this space.');
        }

        // Retrieve all tasks associated with the given project
        $tasks = $project->tasks()->get();

        return view('tasks.index', compact('space', 'project', 'tasks'));
    }

    /**
     * Show the form for creating a new task.
     *
     * @param  \App\Models\Space  $space
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function create(Space $space, Project $project)
    {
        // Authorize that the current user has 'Admin' or 'Project Manager' role in the space
        $this->authorizeRole($space, ['Admin', 'Project Manager']);

        return view('tasks.create', compact('space', 'project'));
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Space  $space
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Space $space, Project $project)
    {
        // Authorize that the current user has 'Admin' or 'Project Manager' role in the space
        $this->authorizeRole($space, ['Admin', 'Project Manager']);

        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Changed 'deadline' to 'due_date' for consistency with Task model's $fillable
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        // Create a new task associated with the project
        $project->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date, // Use 'due_date'
            'priority' => $request->priority,
            'status' => 'pending', // Default status for new tasks
        ]);

        return redirect()->route('tasks.index', [$space, $project])->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  \App\Models\Space  $space
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\View\View
     */
    public function edit(Space $space, Project $project, Task $task)
    {
        // Authorize that the current user has 'Admin' or 'Project Manager' role in the space
        $this->authorizeRole($space, ['Admin', 'Project Manager']);

        return view('tasks.edit', compact('space', 'project', 'task'));
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Space  $space
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Space $space, Project $project, Task $task)
    {
        // Authorize that the current user has 'Admin' or 'Project Manager' role in the space
        $this->authorizeRole($space, ['Admin', 'Project Manager']);

        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Changed 'deadline' to 'due_date' for consistency with Task model's $fillable
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        // Update the task with the validated data
        // Changed 'deadline' to 'due_date'
        $task->update($request->only(['title', 'description', 'due_date', 'priority', 'status']));

        return redirect()->route('tasks.index', [$space, $project])->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Space  $space
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Space $space, Project $project, Task $task)
    {
        // Authorize that the current user has 'Admin' or 'Project Manager' role in the space
        $this->authorizeRole($space, ['Admin', 'Project Manager']);

        // Delete the task
        $task->delete();

        return redirect()->route('tasks.index', [$space, $project])->with('success', 'Task deleted successfully.');
    }

    /**
     * Member can update status of their assigned task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Space  $space
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Space $space, Project $project, Task $task)
    {
        $user = $request->user(); // Get the authenticated user

        // First, check if the user is actually assigned to this task
        if (!$task->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not assigned to this task.');
        }

        // Get the user's pivot data for their role within the specific space
        $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;
        // Get the ID for the 'Member' role
        $memberRoleId = Role::where('name', 'Member')->value('id');

        // Check if the user is a member of the space and if their role is 'Member'
        if (!$pivot || $pivot->role_id !== $memberRoleId) {
            abort(403, 'Only Members can update task progress.');
        }

        // Validate the new status
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        // Update the task status and save
        $task->status = $request->status;
        $task->save();

        return redirect()->back()->with('success', 'Task status updated.');
    }

    /**
     * Utility method to check if the authenticated user has one of the allowed roles in the given space.
     *
     * @param  \App\Models\Space  $space
     * @param  array  $allowedRoles  An array of role names (e.g., ['Admin', 'Project Manager'])
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    private function authorizeRole(Space $space, array $allowedRoles)
    {
        $user = Auth::user(); // Get the authenticated user

        // Retrieve the pivot data for the user's role within the space
        $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;

        // If the user is not associated with this space, deny access
        if (!$pivot) {
            abort(403, 'You are not a member of this space.');
        }

        // Get the IDs of the allowed roles
        $allowedRoleIds = Role::whereIn('name', $allowedRoles)->pluck('id')->toArray();

        // Check if the user's role ID in the space is among the allowed role IDs
        if (!in_array($pivot->role_id, $allowedRoleIds)) {
            abort(403, 'You do not have permission.');
        }
    }
}
