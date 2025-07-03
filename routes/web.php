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

    // Spaces
    Route::resource('spaces', SpaceController::class);
    Route::post('/spaces/{space}/leave', [SpaceController::class, 'leave'])->name('spaces.leave');

    // Projects under spaces
    Route::get('/spaces/{space}/projects', [ProjectController::class, 'index'])->name('spaces.projects.index');
    Route::resource('spaces.projects', ProjectController::class)->except(['index']);

    // Assign project manager
    Route::patch('/projects/{project}/assign-manager', [ProjectController::class, 'assignManager'])->name('projects.assignManager');

    // Assign/remove employees
    Route::post('/projects/{project}/employees', [ProjectController::class, 'addEmployee'])->name('projects.employees.add');
    Route::delete('/projects/{project}/employees/{user}', [ProjectController::class, 'removeEmployee'])->name('projects.employees.remove');

    // Tasks nested under projects
    Route::resource('projects.tasks', TaskController::class)->except('show');

    // Comments on tasks
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Invites
    Route::post('/spaces/{space}/invite', [InviteController::class, 'generate'])->name('spaces.invite.generate');
    Route::get('/spaces/{space}/invite-link', [InviteController::class, 'showLink'])->name('spaces.invite.link');
    Route::get('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('/join-invite', [InviteController::class, 'handleJoin'])->name('invite.handle');
});
