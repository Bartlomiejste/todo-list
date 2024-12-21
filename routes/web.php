<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/tasks');
    });
    Route::resource('tasks', TaskController::class);

    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
});

Route::get('/tasks/{task}/history', [TaskController::class, 'history'])->name('tasks.history');
Route::post('/tasks/{task}/generate-link', [TaskController::class, 'generateLink'])->name('tasks.generateLink');
Route::get('/tasks/public/{task}', [TaskController::class, 'viewPublicTask'])->name('tasks.public');
Route::post('/tasks/{task}/sync-google-calendar', [TaskController::class, 'syncWithGoogleCalendar'])->name('tasks.syncGoogleCalendar');