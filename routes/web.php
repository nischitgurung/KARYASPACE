<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\InviteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', fn () => view('welcome'));
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// Authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Spaces resource routes
    Route::resource('spaces', SpaceController::class);

    // Leave a space
    Route::post('/spaces/{space}/leave', [SpaceController::class, 'leave'])->name('spaces.leave');

    // Projects nested under spaces (full resource)
    Route::resource('spaces.projects', ProjectController::class);

    // Add employee to project
    Route::post('/spaces/{space}/projects/{project}/add-employee', [ProjectController::class, 'addEmployee'])
        ->name('spaces.projects.addEmployee');

    // Remove employee from project
    Route::delete('/spaces/{space}/projects/{project}/remove-employee/{user}', [ProjectController::class, 'removeEmployee'])
        ->name('spaces.projects.removeEmployee');

    // Assign project manager (optional)
    Route::patch('/projects/{project}/assign-manager', [ProjectController::class, 'assignManager'])->name('projects.assignManager');

    // Tasks nested under projects (except show)
    Route::resource('projects.tasks', TaskController::class)->except('show');

    // Comments on tasks
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Invites routes
    Route::post('/spaces/{space}/invite', [InviteController::class, 'generate'])->name('spaces.invite.generate');
    Route::get('/spaces/{space}/invite-link', [InviteController::class, 'showLink'])->name('spaces.invite.link');
    Route::get('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('/join-invite', [InviteController::class, 'handleJoin'])->name('invite.handle');
});
