<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Member')->only(['store']);
    }

    // Retrieve comments for a specific task
    public function index(Task $task)
    {
        $this->authorizeAccess($task);
        return $task->comments()->with('user')->latest()->paginate(10);
    }

    //  Allow members to add comments to assigned tasks
    public function store(Request $request, Task $task)
    {
        $this->authorizeAccess($task);

        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Comment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Comment added successfully.']);
    }

    // Allow users to edit their own comments
    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own comments.');
        }

        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment->update(['content' => $request->content]);

        return response()->json(['message' => 'Comment updated successfully.']);
    }

    // Allow users to delete their own comments
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own comments.');
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully.']);
    }

    // Helper function to check access rights
    private function authorizeAccess(Task $task)
    {
        if (!Auth::user()->tasks()->where('task_id', $task->id)->exists()) {
            abort(403, 'You can only interact with assigned tasks.');
        }
    }
}

