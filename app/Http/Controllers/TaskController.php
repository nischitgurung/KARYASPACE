<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; // ✅ <--- This line is required

class TaskController extends Controller
{
 public function index()
{
    $recentTasks = Task::with('users')->latest()->take(5)->get();

    // Pass data to the view
    return view('dashboard', [
        'recentTasks' => $recentTasks,
        // other variables
    ]);
}

}
