<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
// app/Http/Controllers/ProjectController.php

public function create(Space $space)
{
    return view('projects.create', compact('space'));
}

public function store(Request $request, Space $space)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $space->projects()->create([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    return redirect()->route('spaces.index')->with('success', 'Project created successfully!');
}


}
