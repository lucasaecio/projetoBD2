<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippersController;
use App\Http\Controllers\SupplierController;
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

Route::get('/', function () {
    echo "true";
});

Route::prefix('/products')->group(function () {
    Route::get('', [ProductController::class, 'index'])->name('product@index');
    Route::get('/dashboard/{SupplierID}', [ProductController::class, 'dashboard'])->name('product@dashboard');
});

Route::prefix('/shippers')->group(function () {
    Route::get('', [ShippersController::class, 'index'])->name('shipper@index');
});

Route::prefix('/employees')->group(function () {
    Route::get('', [EmployeeController::class, 'index'])->name('employee@index');
});

Route::prefix('/suppliers')->group(function () {
    Route::get('', [SupplierController::class, 'index'])->name('supplier@index');
});


Route::prefix('/customers')->group(function () {
    Route::get('', [CustomerController::class, 'index'])->name('customer@index');
    Route::get('/{id}', [CustomerController::class, 'show'])->name('customer@show');
    Route::post('', [CustomerController::class, 'store'])->name('customer@store');
    Route::put('/{id}', [CustomerController::class, 'update'])->name('customer@update');
    Route::patch('/{id}', [CustomerController::class, 'update'])->name('customer@update');
    Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customer@destroy');
});

Route::prefix('/orders')->group(function () {
    Route::get('', [OrderController::class, 'index'])->name('order@index');
    Route::get('/{id}', [OrderController::class, 'show'])->name('order@show');
    Route::post('', [OrderController::class, 'store'])->name('order@store');
    Route::put('/{id}', [OrderController::class, 'update'])->name('order@update');
    Route::patch('/{id}', [OrderController::class, 'update'])->name('order@update');
    Route::delete('/{id}', [OrderController::class, 'destroy'])->name('order@destroy');
});
