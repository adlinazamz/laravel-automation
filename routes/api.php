<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\ProfileApiController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Api\Auth\PasswordApiController;
use App\Http\Controllers\Api\Auth\NewPasswordApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//products
        Route::get('/products', [App\Http\Controllers\Api\ProductApiController::class, 'index'])->name('api.products.index');
        Route::post('/products', [App\Http\Controllers\Api\ProductApiController::class, 'store'])->name('api.products.store');
        Route::get('/products/{product}', [App\Http\Controllers\Api\ProductApiController::class, 'show'])->name('api.products.show');
        Route::put('/products/{product}', [App\Http\Controllers\Api\ProductApiController::class, 'update'])->name('api.products.update');
        Route::post('/products/{product}', [ProductApiController::class, 'updateViaPost'])->name('api.products.updateViaPost');

        Route::delete('/products/{product}', [App\Http\Controllers\Api\ProductApiController::class, 'destroy'])->name('api.products.destroy');
        //import export excel
        Route::get('/products-export',[App\Http\Controllers\Api\ProductApiController::class, 'export'])->name('api.products.export');
        Route::post('/products-import', [App\Http\Controllers\Api\ProductApiController::class, 'import'])->name('api.products.import');
//});

//Auth folder
Route::put('/password', [PasswordApiController::class, 'update'])->name('api.password.update');
Route::get('/reset-password/{token}', [NewPasswordApiController::class, 'create'])
                ->name('api.password.reset');
Route::post('/reset-password', [NewPasswordApiController::class, 'store']);