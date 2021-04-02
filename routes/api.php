<?php

use App\Http\Helpers\MetaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/customers/search', function (Request $request) {
        return \App\Models\Customer::where('name', 'like', '%' . $request->input('q', '') . '%')->get();
    });

    Route::get('/product_types', function () {
        return \App\Models\ProductType::all('name');
    });
    Route::get('/product_designs', function () {
        return \App\Models\ProductDesign::all();
    });

    Route::get('/pawn-configs', function () {
        return [
            'goldprice' => goldprice(),
            'pawn_life' => MetaHelper::get('pawn_life', '3'),
            'pawn_int_default_rate' => MetaHelper::get('pawn_int_default_rate', 3),
            'pawn_int_min' => MetaHelper::get('pawn_int_min', 50),
            'pawn_int_range_rates_enable' => MetaHelper::get('pawn_int_range_rates_enable', false),
            'pawn_int_discount_rates_enable' => MetaHelper::get('pawn_int_discount_rates_enable', false),

        ];
    });
    Route::namespace('\App\Http\Controllers\Api')
        ->name('api.')
        ->group(function () {

            Route::resource('banks', 'BankController');
            Route::resource('bank-accounts', 'BankAccountController');

            Route::post('/pawns/action/{pawn}', [\App\Http\Controllers\Api\PawnController::class,'storeAction'])->name('pawns.storeAction');
            Route::get('/pawns/print/{pawn}', [\App\Http\Controllers\Api\PawnController::class, 'print_ticket'])->name('pawns.print');
            Route::get('/pawns/today-int/{pawn}', [\App\Http\Controllers\Api\PawnController::class, 'getTodayInt'])->name('pawns.todayInt');
            Route::resource('pawns', 'PawnController');
            Route::resource('pawn-int-receives', 'PawnIntReceiveController');

        });

    Route::get('/goldprice', function () {
        return goldprice();
    });

    Route::resource('payment-methods', \App\Http\Controllers\PaymentMethodController::class);


});
