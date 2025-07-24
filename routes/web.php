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

    // --- Dashboard ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Top-Level Space Routes ---
    Route::resource('spaces', SpaceController::class);
    Route::post('/spaces/{space}/leave', [SpaceController::class, 'leave'])->name('spaces.leave');

    // --- Group for ALL routes happening inside a specific Space ---
    Route::prefix('spaces/{space}')->name('spaces.')->group(function () {

        // --- Invite routes (Scoped to the space) ---
        Route::post('/invite', [InviteController::class, 'generate'])->name('invite.generate');
        Route::get('/invite-link', [InviteController::class, 'showLink'])->name('invite.link');

        // --- Space Members (Admin only) ---
        Route::middleware('space.role:Admin')->prefix('members')->name('members.')->group(function () {
            Route::get('/', [SpaceUserController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [SpaceUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [SpaceUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [SpaceUserController::class, 'destroy'])->name('destroy');
        });

        // --- Group for routes related to Projects within the space ---
        Route::prefix('projects')->name('projects.')->group(function() {
            // Define the resource controller routes for projects
            Route::resource('/', ProjectController::class)->parameters(['' => 'project']);

            // --- Group for routes related to a specific Project ---
            Route::prefix('{project}')->group(function() {

                // --- Admin & Project Manager Actions ---
                Route::middleware('space.role:Admin,Project Manager')->group(function () {
                    // Project employee management
                    Route::post('/add-employee', [ProjectController::class, 'addEmployee'])->name('addEmployee');
                    Route::delete('/remove-employee/{user}', [ProjectController::class, 'removeEmployee'])->name('removeEmployee');

                    // Assign project manager
                    Route::patch('/assign-manager', [ProjectController::class, 'assignManager'])->name('assignManager');

                    // Task CRUD Routes (Create, Store, Edit, Update, Destroy)
                    Route::resource('tasks', TaskController::class)->except(['index', 'show']);
                });

                // --- Member-only Actions ---
                Route::middleware('space.role:Member')->group(function () {
                    // Task Progress Update
                    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
                });
            });
        });
    });

    // --- Routes that DO NOT depend on a space ---

    // Comments on tasks (only needs the task ID)
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Invite acceptance routes (token contains all necessary info)
    Route::get('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('/join-invite', [InviteController::class, 'handleJoin'])->name('invite.handle');
});