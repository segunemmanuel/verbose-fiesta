<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes(['register'=>false,'reset'=>false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [WelcomeController::class, 'showWelcomePage'])->name('welcome');

Route::get('authorization', [LoginController::class, 'authorization'])->name('authorization');

Route::get('products/{title}-{id}', [ProductController::class, 'showProduct'])->name('products.show');



Route::get('products/{title}-{id}/purchase', [ProductController::class, 'buyProduct'])->name('products.buy');

Route::get('products/publish', [ProductController::class, 'showPublishProductForm'])->name('products.publish');

Route::post('products/publish', [ProductController::class, 'showPublishProductForm']);




Route::get('categories/{title}-{id}/products', [CategoryProductController::class, 'showProducts'])->name('categories.products.show');


Route::get('/home/products', 'HomeController@showProducts')->name('products');
Route::get('/home/purchases', 'HomeController@showPurchases')->name('purchases');



Route::post('products/publish', 'ProductController@publishProduct');


