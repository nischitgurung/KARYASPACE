<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    SpaceController,
    ProjectController,
    TaskController,
    CommentController,
    InviteController,
    SpaceUserController
};

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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Space management (except update)
    Route::resource('spaces', SpaceController::class)->except(['update']);
    Route::put('spaces/{space}', [SpaceController::class, 'update'])->name('spaces.update');

    // Scoped routes for a specific space
    Route::prefix('spaces/{space}')->name('spaces.')->group(function () {

        // Projects routes within space
        Route::delete('projects/{project}', [ProjectController::class, 'destroy'])
            ->middleware('space.role:admin')
            ->name('projects.destroy');

        Route::middleware('space.role:admin')->group(function () {
            Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
            Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
        });

        Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

        Route::middleware('space.role:admin,project_manager')->group(function () {
            Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
            Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        });

        // Tasks index accessible to Admin, Project Manager, Member
        Route::get('projects/{project}/tasks', [TaskController::class, 'index'])
            ->middleware('space.role:admin,project_manager,member')
            ->name('projects.tasks.index');

        // Tasks resource routes except index and show for Admin and Project Manager
        Route::middleware('space.role:admin,project_manager')->group(function () {
            Route::resource('projects.tasks', TaskController::class)->except(['index', 'show']);
        });

        // Members management for Admin only
        Route::middleware('space.role:admin')->prefix('members')->name('members.')->group(function () {
            Route::get('/', [SpaceUserController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [SpaceUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [SpaceUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [SpaceUserController::class, 'destroy'])->name('destroy');
        });

        // Assign employees, managers, etc. Admin + Project Manager
        Route::middleware('space.role:admin,project_manager')->group(function () {
            Route::post('projects/{project}/add-employee', [ProjectController::class, 'addEmployee'])->name('projects.addEmployee');
            Route::delete('projects/{project}/remove-employee/{user}', [ProjectController::class, 'removeEmployee'])->name('projects.removeEmployee');
            Route::patch('projects/{project}/assign-manager', [ProjectController::class, 'assignManager'])->name('projects.assignManager');
        });

        // Member-only route for updating task status
        Route::middleware('space.role:member')->patch('projects/{project}/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('projects.tasks.updateStatus');

        // Project-specific invite link
        Route::get('projects/{project}/invite-link', [InviteController::class, 'projectInviteLink'])->name('projects.invite.link');

        // Space-wide invite links and generation
        Route::get('/invite-link', [InviteController::class, 'showLink'])->name('invite.link');
        Route::post('/invite', [InviteController::class, 'generate'])->name('invite.generate');
    });

    // Comments routes
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Invite accept flow
    Route::get('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('/join-invite', [InviteController::class, 'handleJoin'])->name('invite.handle');
});
