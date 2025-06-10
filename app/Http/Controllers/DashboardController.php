<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;

class DashboardController extends Controller
{
public function index()
{
    $totalProjects = Project::count();
    $totalTasks = Task::count();
    $completedTasks = Task::where('status', 'completed')->count();
    $recentTasks = Task::latest()->take(5)->get();

    return view('dashboard', compact('totalProjects', 'totalTasks', 'completedTasks', 'recentTasks'));
}

}
