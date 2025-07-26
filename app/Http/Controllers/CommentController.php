<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Task, Comment};
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $spaceId, $projectId, $taskId)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $task = Task::findOrFail($taskId);

        // You can add authorization checks here if needed

        $comment = Comment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }
}
