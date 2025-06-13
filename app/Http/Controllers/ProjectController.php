<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin')->only(['store', 'update', 'destroy']);
    }

    // Retrieve projects that the user has access to
    public function index()
    {
        $user = Auth::user();
        return Project::whereIn('space_id', $user->spaces->pluck('id'))->paginate(10);
    }

    //  Ensure only Admins can create projects within their space
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if (!$request->user()->spaces()->wherePivot('role', 'Admin')->exists()) {
            abort(403, 'Only Admins can create projects.');
        }

        return Project::create($request->all());
    }

    // Restrict project updates to Admins only
    public function update(Request $request, Project $project)
    {
        if (!$request->user()->spaces()->wherePivot('role', 'Admin')->exists()) {
            abort(403, 'Only Admins can modify projects.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($request->all());
        return response()->json(['message' => 'Project updated successfully.']);
    }

    //  Soft delete a project instead of permanent deletion
    public function destroy(Project $project)
    {
        if (!$request->user()->spaces()->wherePivot('role', 'Admin')->exists()) {
            abort(403, 'Only Admins can delete projects.');
        }

        $project->delete(); // Soft delete enabled
        return response()->json(['message' => 'Project archived successfully.']);
    }
}
