<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use Illuminate\Support\Facades\Auth;

class SpaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin')->only(['store', 'update', 'destroy']);
    }

    //  Retrieve spaces where the user is assigned
    public function index()
    {
        $user = Auth::user();
        return Space::whereIn('id', $user->spaces->pluck('id'))->paginate(10);
    }

    // Ensure only Admins can create spaces
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if (!$request->user()->hasRole('Admin')) {
            abort(403, 'Only Admins can create spaces.');
        }

        return Space::create($request->all());
    }

    // Restrict space updates to Admins only
    public function update(Request $request, Space $space)
    {
        if (!$request->user()->spaces()->wherePivot('role', 'Admin')->exists()) {
            abort(403, 'Only Admins can modify spaces.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $space->update($request->all());
        return response()->json(['message' => 'Space updated successfully.']);
    }

    //  Soft delete a space instead of permanent deletion
    public function destroy(Space $space)
    {
        if (!$request->user()->spaces()->wherePivot('role', 'Admin')->exists()) {
            abort(403, 'Only Admins can delete spaces.');
        }

        $space->delete();
        return response()->json(['message' => 'Space archived successfully.']);
    }
}
