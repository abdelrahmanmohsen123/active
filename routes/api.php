<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\authController;
use App\Http\Controllers\api\auth\CartController;
use App\Http\Controllers\api\auth\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix'=>'users'],function(){
    Route::post('register',[authController::class,'register']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [authController::class,'register']);
    Route::post('login', [authController::class,'login']);


});
Route::group(['prefix'=>'users','middleware' => 'api'],function(){
    Route::get('cart-list', [CartController::class, 'cartList'])->name('cart');
    Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add.to.cart');
    Route::post('update-cart', [CartController::class, 'update'])->name('update.cart');
    Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('remove.from.cart');
});

