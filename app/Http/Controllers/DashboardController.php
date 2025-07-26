<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Illuminate\View\View;


class DashboardController extends Controller
{
public function index()
{
    $totalProjects = Project::count();
    $totalTasks = Task::whereIn('status', ['to_do', 'in_progress'])->count();

    $completedTasks = Task::where('status', 'done')->count();
    $recentTasks = Task::latest()->take(5)->get();



    return view('dashboard', compact('totalProjects', 'totalTasks', 'completedTasks', 'recentTasks'));


    
}



}
