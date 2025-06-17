<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progress;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Member')->only(['store', 'update']);
    }

    //  Retrieve progress updates for a specific task
    public function index(Task $task)
    {
        $this->authorizeAccess($task);
        return $task->progress()->latest()->paginate(10);
    }

    // Allow assigned members to add progress updates
    public function store(Request $request, Task $task)
    {
        $this->authorizeAccess($task);

        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'description' => 'nullable|string|max:500',
        ]);

        Progress::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Progress update added successfully.']);
    }

    //  Allow assigned members to update their progress updates
    public function update(Request $request, Progress $progress)
    {
        if ($progress->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own progress updates.');
        }

        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'description' => 'nullable|string|max:500',
        ]);

        $progress->update($request->all());

        return response()->json(['message' => 'Progress updated successfully.']);
    }

    // Helper function to check access rights
    private function authorizeAccess(Task $task)
    {
        if (!Auth::user()->tasks()->where('task_id', $task->id)->exists()) {
            abort(403, 'You can only modify progress on assigned tasks.');
        }
    }
}

