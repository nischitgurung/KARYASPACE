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

    // Spaces resource
    Route::resource('spaces', SpaceController::class);
    Route::post('/spaces/{space}/leave', [SpaceController::class, 'leave'])->name('spaces.leave');

    // Projects nested in spaces
    Route::resource('spaces.projects', ProjectController::class);

    // Project employee management
    Route::post('/spaces/{space}/projects/{project}/add-employee', [ProjectController::class, 'addEmployee'])
        ->name('spaces.projects.addEmployee');
    Route::delete('/spaces/{space}/projects/{project}/remove-employee/{user}', [ProjectController::class, 'removeEmployee'])
        ->name('spaces.projects.removeEmployee');

    // Assign project manager
    Route::patch('/projects/{project}/assign-manager', [ProjectController::class, 'assignManager'])
        ->name('projects.assignManager');

    // Comments on tasks
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Invite routes
    Route::post('/spaces/{space}/invite', [InviteController::class, 'generate'])->name('spaces.invite.generate');
    Route::get('/spaces/{space}/invite-link', [InviteController::class, 'showLink'])->name('spaces.invite.link');
    Route::get('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('/join-invite', [InviteController::class, 'handleJoin'])->name('invite.handle');

    /**
     * Space Members (Admin only)
     */
    Route::middleware('space.role:Admin')->group(function () {
        Route::get('/spaces/{space}/members', [SpaceUserController::class, 'index'])->name('spaces.members.index');
        Route::get('/spaces/{space}/members/{user}/edit', [SpaceUserController::class, 'edit'])->name('spaces.members.edit');
        Route::put('/spaces/{space}/members/{user}', [SpaceUserController::class, 'update'])->name('spaces.members.update');
        Route::delete('/spaces/{space}/members/{user}', [SpaceUserController::class, 'destroy'])->name('spaces.members.destroy');
    });

    /**
     * Task CRUD (Admin & Project Manager)
     */
    Route::middleware('space.role:Admin,Project Manager')->group(function () {
        Route::resource('projects.tasks', TaskController::class)->only([
            'create', 'store', 'edit', 'update', 'destroy'
        ]);
    });

    /**
     * Task Progress Update (Member only)
     */
    Route::middleware('space.role:Member')->group(function () {
        Route::patch('/projects/{project}/tasks/{task}/status', [TaskController::class, 'updateStatus'])
            ->name('tasks.updateStatus');
    });
});
