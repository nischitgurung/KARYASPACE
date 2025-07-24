<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\SpaceUserController;

// Public routes
Route::get('/', fn () => view('welcome'))->name('welcome');
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

    // Nested routes inside a specific space
    Route::prefix('spaces/{space}')->name('spaces.')->group(function () {

        // Space invite routes
        Route::post('/invite', [InviteController::class, 'generate'])->name('invite.generate');
        Route::get('/invite-link', [InviteController::class, 'showLink'])->name('invite.link');

        // Space members (admin only)
        Route::middleware('space.role:admin')->prefix('members')->name('members.')->group(function () {
            Route::get('/', [SpaceUserController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [SpaceUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [SpaceUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [SpaceUserController::class, 'destroy'])->name('destroy');
        });

        // Projects resource inside space
        Route::resource('projects', ProjectController::class);

        // Nested tasks resource under projects using dot notation for route names
        Route::resource('projects.tasks', TaskController::class)->except(['index', 'show']);

        // Task index route (visible to all space members)
        Route::get('projects/{project}/tasks', [TaskController::class, 'index'])->name('projects.tasks.index');

        // Project related actions for admin and project manager roles
        Route::middleware('space.role:admin,project_manager')->group(function () {
            Route::post('projects/{project}/add-employee', [ProjectController::class, 'addEmployee'])->name('projects.addEmployee');
            Route::delete('projects/{project}/remove-employee/{user}', [ProjectController::class, 'removeEmployee'])->name('projects.removeEmployee');
            Route::patch('projects/{project}/assign-manager', [ProjectController::class, 'assignManager'])->name('projects.assignManager');
        });

        // Member-only task status update
        Route::middleware('space.role:member')->patch('projects/{project}/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('projects.tasks.updateStatus');

        // Project invite link route
        Route::get('projects/{project}/invite-link', [InviteController::class, 'projectInviteLink'])->name('projects.invite.link');
    });

    // Comments on tasks
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Invite acceptance
    Route::get('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('/join-invite', [InviteController::class, 'handleJoin'])->name('invite.handle');
});
