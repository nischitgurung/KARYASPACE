<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\ProjectController;
use resources\views\welcome;

Route::get('/', function () {
    return view('welcome'); // Remove `view:` prefix
});


Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return ('contact');
})->name('contact');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('spaces', SpaceController::class);

    Route::get('/spaces/{space}/projects/create', [ProjectController::class, 'create'])->name('spaces.projects.create');
    Route::post('/spaces/{space}/projects', [ProjectController::class, 'store'])->name('spaces.projects.store');

    // If you want, move the show route inside auth middleware too
    Route::get('/spaces/{space}', [SpaceController::class, 'show'])->name('spaces.show');

});

// Test route (should probably be protected or removed)
Route::get('/space-test', [SpaceController::class, 'index'])->middleware('auth');
