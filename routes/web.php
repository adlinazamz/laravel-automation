<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\VirtualController;


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

//run automate
//Route::post('/run-automation',[AutomationController::class, 'runAutomation'])->name('run.automation');
Route::get('/tables', [AutomationController::class, 'listTables'])->name('tables.list');
Route::post('/run-virtual', [AutomationController::class, 'runAutomation']);
//virtual
Route::get('/tables', [AutoController::class, 'listTables'])->name('tables.list');
Route::post('/run-auto',[AutoController::class, 'runAuto'])->name('run.auto');

Route::prefix('virtual')->middleware(['web'])->group(function () {
    Route::get('/{table}', [VirtualController::class, 'index'])->name('virtual.index');
    Route::get('/{table}/create', [VirtualController::class, 'create'])->name('virtual.create');
    Route::post('/{table}', [VirtualController::class, 'store'])->name('virtual.store');
    Route::get('/{table}/{id}', [VirtualController::class, 'show'])->name('virtual.show');
    Route::get('/{table}/{id}/edit', [VirtualController::class, 'edit'])->name('virtual.edit');
    Route::put('/{table}/{id}', [VirtualController::class, 'update'])->name('virtual.update');
    Route::delete('/{table}/{id}', [VirtualController::class, 'destroy'])->name('virtual.destroy');
});

//Event auto-generated route
Route::get('/event', [App\Http\Controllers\EventController::class, 'index'])->name('event.index');
Route::get('/event/create',[App\Http\Controllers\EventController::class, 'create'])->name('event.create');
Route::post('/event', [App\Http\Controllers\EventController::class, 'store'])->name('event.store');
Route::get('/event/{id}', [App\Http\Controllers\EventController::class, 'show'])->name('event.show');
Route::get('/event/{id}/edit', [App\Http\Controllers\EventController::class, 'edit'])->name('event.edit');
Route::put('/event/{id}', [App\Http\Controllers\EventController::class, 'update'])->name('event.update');
Route::delete('/event/{id}', [App\Http\Controllers\EventController::class, 'destroy'])->name('event.destroy');

Route::prefix('virtual')->middleware(['web'])->group(function () {
    Route::get('/{table}', [VirtualController::class, 'index'])->name('virtual.index');
    Route::get('/{table}/create', [VirtualController::class, 'create'])->name('virtual.create');
    Route::post('/{table}', [VirtualController::class, 'store'])->name('virtual.store');
    Route::get('/{table}/{id}', [VirtualController::class, 'show'])->name('virtual.show');
    Route::get('/{table}/{id}/edit', [VirtualController::class, 'edit'])->name('virtual.edit');
    Route::put('/{table}/{id}', [VirtualController::class, 'update'])->name('virtual.update');
    Route::delete('/{table}/{id}', [VirtualController::class, 'destroy'])->name('virtual.destroy');
});