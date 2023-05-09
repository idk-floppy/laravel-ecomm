<?php

use App\Models\Order;
use App\Models\CartData;
use App\Models\CartItems;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\CartController;

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
    return view('products');
})->name('products');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/profile', function () {
    return view('auth\profile');
})->name('profile');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['web'], 'controller' => CartController::class], function () {
    Route::post('cart/add', 'addItem');
    Route::get('cart/get', 'getItems');
});
