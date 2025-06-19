<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/karya-dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });

// From welcome page nav link to about.blade.php
Route::get('/about', function () {
    return view('about');
})->name('about');


// From welcome page nav link to contact.blade.php
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

use App\Http\Controllers\SpaceController;

Route::resource('spaces', SpaceController::class);

use App\Http\Controllers\ProjectController;

Route::middleware(['auth'])->group(function () {
    Route::resource('spaces', SpaceController::class);
    Route::get('/spaces/{space}/projects/create', [ProjectController::class, 'create'])->name('spaces.projects.create');
    Route::post('/spaces/{space}/projects', [ProjectController::class, 'store'])->name('spaces.projects.store');
});
//test
Route::get('/space-test', [App\Http\Controllers\SpaceController::class, 'index']);
Route::get('/spaces/{space}', [SpaceController::class, 'show'])->name('spaces.show');

;

Route::get('/spaces/{space}/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::post('/spaces/{space}/projects', [ProjectController::class, 'store'])->name('projects.store');

Route::resource('spaces.projects', ProjectController::class);

Route::prefix('spaces/{space}')->group(function () {
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
});


Route::resource('spaces.projects', ProjectController::class)->only(['create', 'store']);

Route::resource('spaces.projects', ProjectController::class);

Route::middleware('auth')->group(function () {
    Route::post('projects/{project}/employees', [ProjectController::class, 'addEmployee'])->name('projects.employees.add');
    Route::delete('projects/{project}/employees/{user}', [ProjectController::class, 'removeEmployee'])->name('projects.employees.remove');
});
Route::resource('spaces.projects', ProjectController::class);
Route::get('/spaces/{space}/projects', [ProjectController::class, 'index'])->name('spaces.projects.index');




Route::patch('/projects/{project}/assign-manager', [ProjectController::class, 'assignManager'])->name('projects.assignManager');
Route::get('/spaces/{space}', [SpaceController::class, 'show'])->name('spaces.show');

