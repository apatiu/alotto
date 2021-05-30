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


    Route::get('/product-designs', function () {
        return \App\Models\ProductDesign::all();
    });


    Route::namespace('\App\Http\Controllers\Api')
        ->name('api.')
        ->group(function () {

            Route::get('/users',function() {
                return \App\Models\User::all();
            });

            Route::resource('customers', CustomerController::class);
            Route::resource('payment-methods', PaymentMethodController::class);

            Route::resource('banks', 'BankController');
            Route::resource('bank-accounts', 'BankAccountController');
            Route::post('/check-product', [\App\Http\Controllers\Api\ProductController::class, 'check'])
                ->name('check-product');
            Route::get(
                '/product-designs/filter/type/{product_type}',
                [\App\Http\Controllers\Api\ProductDesignController::class, 'filterType']
            )->name('product-designs.filter.type');
            Route::resource('product-designs', 'ProductDesignController');
            Route::resource('product-sizes', 'ProductSizeController');

            Route::resource('product-percents', 'ProductPercentController');

            Route::get('/pawn-configs', function () {
                return [
                    'goldprice' => GoldPrice(),
                    'pawn_life' => MetaHelper::get('pawn_life', '3'),
                    'pawn_int_default_rate' => MetaHelper::get('pawn_int_default_rate', 3),
                    'pawn_int_min' => MetaHelper::get('pawn_int_min', 50),
                    'pawn_int_range_rates_enable' => MetaHelper::get('pawn_int_range_rates_enable', false),
                    'pawn_int_discount_rates_enable' => MetaHelper::get('pawn_int_discount_rates_enable', false),

                ];
            });
            Route::get('/pawns/search',[\App\Http\Controllers\Api\PawnController::class,'search'])
                ->name('pawns.search');
            Route::post('/pawns/action/{pawn}', [\App\Http\Controllers\Api\PawnController::class, 'storeAction'])->name('pawns.storeAction');
            Route::get('/pawns/print/{pawn}', [\App\Http\Controllers\Api\PawnController::class, 'print_ticket'])->name('pawns.print');
            Route::get('/pawns/today-int/{pawn}', [\App\Http\Controllers\Api\PawnController::class, 'getTodayInt'])->name('pawns.todayInt');
            Route::resource('pawns', 'PawnController');
            Route::resource('pawn-int-receives', 'PawnIntReceiveController');

            Route::post('/savings/actions/refund/{saving}',[
                \App\Http\Controllers\Api\SavingController::class,'refund'
            ])->name('savings.actions.refund');
            Route::post('/savings/actions/close/{saving}',[
                \App\Http\Controllers\Api\SavingController::class,'close'
            ])->name('savings.actions.close');
            Route::post('/savings/actions/deposit/{saving}',[
                \App\Http\Controllers\Api\SavingController::class,'deposit'
            ])->name('savings.actions.deposit');
            Route::get('/savings/search',[\App\Http\Controllers\Api\SavingController::class,'search'])
                ->name('savings.search');
            Route::resource('savings', 'SavingController');
            Route::resource('saving-details', 'SavingDetailController');

            Route::get('/product-types', function () {
                return \App\Models\ProductType::all();
            })->name('product-types.index');

            Route::get('/goldprice', function () {
                return goldprice();
            })->name('goldprice');
            Route::get('/gold-prices/now', function () {
                return \App\Models\GoldPrice::now();
            })->name('gold-prices.now');

            Route::get('/gold-prices/now', [App\Http\Controllers\Api\GoldPriceController::class, 'now'])
                ->name('gold-prices.now');

            Route::post('/products/search', [\App\Http\Controllers\Api\ProductController::class, 'search'])->name('products.search');

            Route::resource('sales','SaleController');
            Route::resource('payment-types','PaymentTypeController');
            Route::get('/sales/print/guarantee-card/{sale}',
                [\App\Http\Controllers\Api\SaleController::class, 'printGuaranteeCard'])
            ->name('sales.print.guarantee-card');
        });





});
