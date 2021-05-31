<?php

use App\Http\Controllers\BranchMemberController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GoldPercentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDesignController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\StockImportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyConfigController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PosController;
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

Route::get('/test',function() {
    return Inertia::render('Test');
});

Route::get('/bb',function() {
    return Inertia::render('Billboard');
});

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

Route::middleware(['role:manager'])->group(function () {

    Route::resource('company', CompanyController::class);
    Route::resource('company-config', CompanyConfigController::class);


    Route::put('users/{user}/restore', [UserController::class, 'restore'])
        ->name('users.restore');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('users',UserController::class);
    Route::resource('settings', \App\Http\Controllers\SettingController::class);
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    Route::resource('gold-percents', GoldPercentController::class);
    Route::resource('product-types', ProductTypeController::class);
    Route::resource('product-designs', ProductDesignController::class);
    Route::resource('product-sizes', \App\Http\Controllers\ProductSizeController::class);
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('stock-imports', StockImportController::class);
    Route::post('/pawns-config', [\App\Http\Controllers\PawnController::class, 'storeConfig'])->name('pawns-config.store');
    Route::post('/savings-config', [\App\Http\Controllers\SavingController::class, 'storeConfig'])->name('savings-config.store');
    Route::resource('bank-accounts', \App\Http\Controllers\BankAccountController::class);
    Route::resource('payments', \App\Http\Controllers\PaymentController::class);
    Route::resource('oldgoldstocks', \App\Http\Controllers\OldGoldStockCardController::class);
    Route::resource('stock-cards', \App\Http\Controllers\StockCardController::class);

    Route::resource('shifts', \App\Http\Controllers\ShiftController::class);

    Route::get('api-gold_percents', function () {
        return \App\Models\GoldPercent::all();
    });

    Route::get('/rp/day/{d?}', ['\App\Http\Controllers\ReportDayController','index'])
        ->name('rp.day');

});

Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {

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

        Route::middleware(['shift'])->group(function() {

            Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
            Route::resource('sales', \App\Http\Controllers\SaleController::class);
            Route::resource('pawns', \App\Http\Controllers\PawnController::class);
            Route::resource('savings', \App\Http\Controllers\SavingController::class);

            Route::post('shifts/close/{shift}', [\App\Http\Controllers\ShiftController::class, 'close'])->name('shifts.close');
            Route::get('shifts/current', [\App\Http\Controllers\ShiftController::class, 'showCurrent'])->name('shifts.show-current');


        });

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
            Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
            Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
            Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
            Route::post('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
            Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
            Route::delete('/current-team/profile-photo', [TeamController::class, 'destroyCurrentTeamProfilePhoto'])
                ->name('current-team-photo.destroy');
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
