<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Auth\PasswordApiController;
use App\Http\Controllers\Api\Auth\NewPasswordApiController;
use App\Http\Controllers\Api\Auth\RegisteredUserApiController;
use App\Http\Controllers\Api\Auth\AuthenticatedSessionApiController;

use App\Http\Controllers\Api\AuthController;
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


Route::middleware('auth:api')->group(function () {
    //products
    Route::get('/products', [ProductApiController::class, 'index'])->name('api.products.index');
    Route::post('/products', [ProductApiController::class, 'store'])->name('api.products.store');
    Route::get('/products/{product}', [ProductApiController::class, 'show'])->name('api.products.show');
    Route::put('/products/{product}', [ProductApiController::class, 'update'])->name('api.products.update');
    Route::post('/products/{product}', [ProductApiController::class, 'updateViaPost'])->name('api.products.updateViaPost');
    Route::delete('/products/{product}', [ProductApiController::class, 'destroy'])->name('api.products.destroy');

    //import export excel
    Route::get('/products-export', [ProductApiController::class, 'export'])->name('api.products.export');
    Route::post('/products-import', [ProductApiController::class, 'import'])->name('api.products.import');
});
Route::group([
    'middleware'=>'api',
    'prefix' => 'auth'
], function($router){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
});
