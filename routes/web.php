<?php

use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['role:admin'])->group(function () {
    Route::resource('settings', \App\Http\Controllers\SettingController::class);
    Route::resource('company', \App\Http\Controllers\CompanyController::class);

    Route::get('users', [UsersController::class, 'index'])
        ->name('users');

    Route::get('users/create', [UsersController::class, 'create'])
        ->name('users.create');

    Route::post('users', [UsersController::class, 'store'])
        ->name('users.store');

    Route::get('users/{user}/edit', [UsersController::class, 'edit'])
        ->name('users.edit');

    Route::put('users/{user}', [UsersController::class, 'update'])
        ->name('users.update');

    Route::delete('users/{user}', [UsersController::class, 'destroy'])
        ->name('users.destroy');

    Route::put('users/{user}/restore', [UsersController::class, 'restore'])
        ->name('users.restore');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('teammembers/{team}/members', [\App\Http\Controllers\TeamMemberController::class, 'store'])->name('teammembers.store');
});
