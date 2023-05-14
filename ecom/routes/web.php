<?php

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

Auth::routes();

Route::resource('/products', ProductController::class);

Route::get('/', function () {
    return view('products');
})->name('home');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/profile', [UserController::class, 'show'])->middleware('auth')->name('profile');

Route::group(['controller' => CartController::class], function () {
    Route::post('cart/add', 'addItem');
    Route::get('cart/get', 'getItems');
    Route::post('cart/remove', 'removeItem');
});
