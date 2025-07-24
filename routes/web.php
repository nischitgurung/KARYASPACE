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

// --- Public routes ---
Route::view('/', 'welcome');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// --- Authenticated routes ---
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // --- Dashboard ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Space Management ---
    Route::resource('spaces', SpaceController::class);
    Route::post('/spaces/{space}/leave', [SpaceController::class, 'leave'])->name('spaces.leave');

    // --- Scoped routes inside a specific space ---
    Route::prefix('spaces/{space}')->name('spaces.')->group(function () {

        // --- Invite routes ---
        Route::get('/invite-link', [InviteController::class, 'showLink'])->name('invite.link');
        Route::post('/invite', [InviteController::class, 'generate'])->name('invite.generate');

        // --- Space Members (Admin only) ---
        Route::middleware('space.role:Admin')->prefix('members')->name('members.')->group(function () {
            Route::get('/', [SpaceUserController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [SpaceUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [SpaceUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [SpaceUserController::class, 'destroy'])->name('destroy');
        });

        // --- Project Management ---
        Route::prefix('projects')->name('projects.')->group(function () {

            Route::resource('/', ProjectController::class)->parameters(['' => 'project']);

            Route::prefix('{project}')->group(function () {

                // Admin & Project Manager Controls
                Route::middleware('space.role:Admin,Project Manager')->group(function () {
                    Route::post('/add-employee', [ProjectController::class, 'addEmployee'])->name('addEmployee');
                    Route::delete('/remove-employee/{user}', [ProjectController::class, 'removeEmployee'])->name('removeEmployee');
                    Route::patch('/assign-manager', [ProjectController::class, 'assignManager'])->name('assignManager');
                    Route::resource('tasks', TaskController::class)->except(['index', 'show']);
                });

                // Member-Only Task Status Updates
                Route::middleware('space.role:Member')->patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
            });

            // View tasks (open to all space members)
            Route::get('{project}/tasks', [TaskController::class, 'index'])->name('tasks.index');
        });
    });

    // --- Global (non-space-bound) routes ---
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('/join-invite', [InviteController::class, 'handleJoin'])->name('invite.handle');
});
