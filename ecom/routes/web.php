<?php

use App\Http\Controllers\Api\Cart\AddItemToCartController;
use App\Models\Order;
use App\Models\CartData;
use App\Models\CartItems;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Product;

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


Route::group(['controller' => ProductController::class, 'prefix' => 'products', 'as' => 'products.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::get('/{product}', 'show')->name('show');
    Route::get('/{product}/edit', 'edit')->name('edit');
});

Route::get('/', function () {
    return view('products');
})->name('home');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::post('cart/add', AddItemToCartController::class);
Route::group(['controller' => CartController::class], function () {
    Route::get('cart/get', 'getItems');
    Route::post('cart/remove', 'removeItem');
});