<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [App\Http\Controllers\ProductController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//products
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/create',[App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
//import export excel
Route::get('/products-export',[App\Http\Controllers\ProductController::class, 'export'])->name('products.export');
Route::post('/products-import', [App\Http\Controllers\ProductController::class, 'import'])->name('products.import');
Route::post('/reports/export/full', [\App\Http\Controllers\ReportsController::class, 'exportFull'])
     ->name('reports.export.full');

require __DIR__.'/auth.php';

//Tester auto-generated route
Route::get('/tester', [App\Http\Controllers\TesterController::class, 'index'])->name('tester.index');
Route::get('/tester/create',[App\Http\Controllers\TesterController::class, 'create'])->name('tester.create');
Route::post('/tester', [App\Http\Controllers\TesterController::class, 'store'])->name('tester.store');
Route::get('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'show'])->name('tester.show');
Route::get('/tester/{tester}/edit', [App\Http\Controllers\TesterController::class, 'edit'])->name('tester.edit');
Route::put('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'update'])->name('tester.update');
Route::delete('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'destroy'])->name('tester.destroy');
//Tester auto-generated route
Route::get('/tester', [App\Http\Controllers\TesterController::class, 'index'])->name('tester.index');
Route::get('/tester/create',[App\Http\Controllers\TesterController::class, 'create'])->name('tester.create');
Route::post('/tester', [App\Http\Controllers\TesterController::class, 'store'])->name('tester.store');
Route::get('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'show'])->name('tester.show');
Route::get('/tester/{tester}/edit', [App\Http\Controllers\TesterController::class, 'edit'])->name('tester.edit');
Route::put('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'update'])->name('tester.update');
Route::delete('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'destroy'])->name('tester.destroy');
//Tester auto-generated route
Route::get('/tester', [App\Http\Controllers\TesterController::class, 'index'])->name('tester.index');
Route::get('/tester/create',[App\Http\Controllers\TesterController::class, 'create'])->name('tester.create');
Route::post('/tester', [App\Http\Controllers\TesterController::class, 'store'])->name('tester.store');
Route::get('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'show'])->name('tester.show');
Route::get('/tester/{tester}/edit', [App\Http\Controllers\TesterController::class, 'edit'])->name('tester.edit');
Route::put('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'update'])->name('tester.update');
Route::delete('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'destroy'])->name('tester.destroy');
//Tester auto-generated route
Route::get('/tester', [App\Http\Controllers\TesterController::class, 'index'])->name('tester.index');
Route::get('/tester/create',[App\Http\Controllers\TesterController::class, 'create'])->name('tester.create');
Route::post('/tester', [App\Http\Controllers\TesterController::class, 'store'])->name('tester.store');
Route::get('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'show'])->name('tester.show');
Route::get('/tester/{tester}/edit', [App\Http\Controllers\TesterController::class, 'edit'])->name('tester.edit');
Route::put('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'update'])->name('tester.update');
Route::delete('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'destroy'])->name('tester.destroy');
//Tester auto-generated route
Route::get('/tester', [App\Http\Controllers\TesterController::class, 'index'])->name('tester.index');
Route::get('/tester/create',[App\Http\Controllers\TesterController::class, 'create'])->name('tester.create');
Route::post('/tester', [App\Http\Controllers\TesterController::class, 'store'])->name('tester.store');
Route::get('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'show'])->name('tester.show');
Route::get('/tester/{tester}/edit', [App\Http\Controllers\TesterController::class, 'edit'])->name('tester.edit');
Route::put('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'update'])->name('tester.update');
Route::delete('/tester/{tester}', [App\Http\Controllers\TesterController::class, 'destroy'])->name('tester.destroy');