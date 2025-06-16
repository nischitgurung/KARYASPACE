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

Route::middleware(['auth'])->group(function () {
    Route::get('/karya-dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// From welcome page nav link to about.blade.php
Route::get('/about', function () {
    return view('about');
})->middleware(['auth'])->name('about');


// From welcome page nav link to contact.blade.php
Route::get('/contact', function () {
    return view('contact');
})->middleware(['auth'])->name('contact');

// From dashboard page nav link to space.blade.php
Route::get('/space', function () {
    return view('space');
})->middleware(['auth'])->name('space');