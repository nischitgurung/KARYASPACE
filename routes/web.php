<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', function () {
    return view('welcome');
});

// Static pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Dashboard (Protected by Jetstream)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Test route
Route::get('/space-test', [SpaceController::class, 'index']);

// Auth-protected routes
Route::middleware('auth')->group(function () {

    // Spaces
    Route::resource('spaces', SpaceController::class);

    // Projects under a specific space
    Route::prefix('spaces/{space}')->group(function () {
        Route::get('/projects', [ProjectController::class, 'index'])->name('spaces.projects.index');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    });

    // Nested resource for create/store (only these two for now)
    Route::resource('spaces.projects', ProjectController::class)->only(['create', 'store']);

    // Employee assignment routes
    Route::post('/projects/{project}/employees', [ProjectController::class, 'addEmployee'])->name('projects.employees.add');
    Route::delete('/projects/{project}/employees/{user}', [ProjectController::class, 'removeEmployee'])->name('projects.employees.remove');

    // Assign manager to project
    Route::patch('/projects/{project}/assign-manager', [ProjectController::class, 'assignManager'])->name('projects.assignManager');
});
