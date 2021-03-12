<?php

use App\Http\Controllers\BranchMemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyConfigController;
use App\Http\Controllers\TeamController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Jetstream\Http\Controllers\CurrentTeamController;
use Laravel\Jetstream\Http\Controllers\Inertia\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;
use Laravel\Jetstream\Http\Controllers\Inertia\OtherBrowserSessionsController;
use Laravel\Jetstream\Http\Controllers\Inertia\PrivacyPolicyController;
use Laravel\Jetstream\Http\Controllers\Inertia\ProfilePhotoController;
use Laravel\Jetstream\Http\Controllers\Inertia\TeamMemberController;
use Laravel\Jetstream\Http\Controllers\Inertia\TermsOfServiceController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;
use Laravel\Jetstream\Http\Controllers\TeamInvitationController;
use Laravel\Jetstream\Jetstream;

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
    Route::resource('company', CompanyController::class);
    Route::resource('company-config', CompanyConfigController::class);

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
});

Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {

    Route::resource('products',ProductController::class);

    if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
        Route::get('/terms-of-service', [TermsOfServiceController::class, 'show'])->name('terms.show');
        Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('policy.show');
    }

    Route::group(['middleware' => ['auth', 'verified']], function () {
        // User & Profile...
        Route::get('/user/profile', [UserProfileController::class, 'show'])
            ->name('profile.show');

        Route::delete('/user/other-browser-sessions', [OtherBrowserSessionsController::class, 'destroy'])
            ->name('other-browser-sessions.destroy');

        Route::delete('/user/profile-photo', [ProfilePhotoController::class, 'destroy'])
            ->name('current-user-photo.destroy');

        if (Jetstream::hasAccountDeletionFeatures()) {
            Route::delete('/user', [CurrentUserController::class, 'destroy'])
                ->name('current-user.destroy');
        }

        // API...
        if (Jetstream::hasApiFeatures()) {
            Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
            Route::post('/user/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
            Route::put('/user/api-tokens/{token}', [ApiTokenController::class, 'update'])->name('api-tokens.update');
            Route::delete('/user/api-tokens/{token}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
        }

        // Teams...
        if (Jetstream::hasTeamFeatures()) {
            Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
            Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
            Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
            Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
            Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
            Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');
            Route::post('/teams/{team}/members', [BranchMemberController::class, 'store'])->name('team-members.store');
            Route::put('/teams/{team}/members/{user}', [TeamMemberController::class, 'update'])->name('team-members.update');
            Route::delete('/teams/{team}/members/{user}', [TeamMemberController::class, 'destroy'])->name('team-members.destroy');

            Route::get('/team-invitations/{invitation}', [TeamInvitationController::class, 'accept'])
                ->middleware(['signed'])
                ->name('team-invitations.accept');

            Route::delete('/team-invitations/{invitation}', [TeamInvitationController::class, 'destroy'])
                ->name('team-invitations.destroy');
        }
    });
});
