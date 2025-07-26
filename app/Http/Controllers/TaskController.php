<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Task, Project, Space, Role};
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
   public function index(Space $space, Project $project)
{
    $this->authorizeRole($space, ['Admin', 'Project Manager', 'Member']);

    if ($project->space_id !== $space->id) {
        abort(404, 'Project does not belong to this space.');
    }

    $tasks = $project->tasks()->latest('due_date')->get();

    // Calculate total weightage of tasks in this project
    $totalWeightage = $tasks->sum('weightage');

    return view('tasks.index', compact('space', 'project', 'tasks', 'totalWeightage'));
}


    public function create(Space $space, Project $project)
    {
        $this->authorizeRole($space, ['Admin', 'Project Manager']);
        return view('tasks.create', compact('space', 'project'));
    }

   public function store(Request $request, Space $space, Project $project)
{
    // Validate input first
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:to_do,in_progress,done',
        'priority' => 'nullable|in:low,medium,high,urgent',
        'due_date' => 'nullable|date|after_or_equal:today',
        'weightage' => 'required|integer|min:1|max:100',
    ]);

    $newWeightage = (int) $request->weightage;

    // Calculate current total weightage of tasks in this project
    $totalWeightage = $project->tasks()->sum('weightage');

    if ($totalWeightage + $newWeightage > 100) {
        return back()->withErrors([
            'weightage' => 'Total weightage of all tasks cannot exceed 100%. You must reduce the weightage or create a new project.'
        ])->withInput();
    }

    // Proceed to create task if validation passes
    $task = $project->tasks()->create([
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status,
        'priority' => $request->priority,
        'due_date' => $request->due_date,
        'weightage' => $newWeightage,
    ]);

    return redirect()->route('spaces.projects.tasks.index', ['space' => $space->id, 'project' => $project->id])
                     ->with('success', 'Task created successfully.');
}


    public function edit(Space $space, Project $project, Task $task)
    {
        $this->authorizeRole($space, ['Admin', 'Project Manager']);
         return view('tasks.edit', compact('space', 'project', 'task'));    }

    public function update(Request $request, Space $space, Project $project, Task $task)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:to_do,in_progress,done',
        'priority' => 'nullable|in:low,medium,high,urgent',
        'due_date' => 'nullable|date|after_or_equal:today',
        'weightage' => 'required|integer|min:1|max:100',
    ]);

    $newWeightage = (int) $request->weightage;

    // Sum all tasks' weightage except current task
    $totalWeightageExcludingCurrent = $project->tasks()
        ->where('id', '!=', $task->id)
        ->sum('weightage');

    if ($totalWeightageExcludingCurrent + $newWeightage > 100) {
        return back()->withErrors([
            'weightage' => 'Total weightage of all tasks cannot exceed 100%. Please adjust the weightage.'
        ])->withInput();
    }

    $task->update([
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status,
        'priority' => $request->priority,
        'due_date' => $request->due_date,
        'weightage' => $newWeightage,
    ]);

    return redirect()->route('spaces.projects.tasks.index', ['space' => $space->id, 'project' => $project->id])
                     ->with('success', 'Task updated successfully.');
}


    public function destroy(Space $space, Project $project, Task $task)
    {
        $this->authorizeRole($space, ['Admin', 'Project Manager']);
        $task->delete();

        return redirect()->route('spaces.projects.tasks.index', [$space, $project])
                         ->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Space $space, Project $project, Task $task)
    {
        $user = $request->user();

        if (!$task->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not assigned to this task.');
        }

        $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;
        $memberRoleId = Role::where('name', 'Member')->value('id');

        if (!$pivot || $pivot->role_id !== $memberRoleId) {
            abort(403, 'Only Members can update task progress.');
        }

        $request->validate([
            'status' => 'required|in:to_do,in_progress,done',
        ]);

        $task->status = $request->status;
        $task->save();

        return redirect()->back()->with('success', 'Task status updated.');
    }

    private function authorizeRole(Space $space, array $allowedRoles)
    {
        $user = Auth::user();

        if ($space->created_by === $user->id) return;

        $project = request()->route('project');
        if ($project && $project->project_manager_id === $user->id) return;

        $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;
        if (!$pivot) abort(403, 'You are not a member of this space.');

        $allowedRoleIds = Role::whereIn('name', $allowedRoles)->pluck('id')->toArray();
        if (!in_array($pivot->role_id, $allowedRoleIds)) {
            abort(403, 'You do not have permission.');
        }
    }
}
