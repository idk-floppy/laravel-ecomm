<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Models\CartData;
use App\Models\CartItems;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
