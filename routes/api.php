<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MainPageController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\ProductController;
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
Route::post('/add/brand',[BrandController::class,'addBrand']);
Route::post('/update/product/{id}',[BrandController::class,'updateProduct']);
Route::post('/info',[BrandController::class,'info']);


Route::group(['namespace' => 'user','prefix'=> 'user'],function (){
    Route::get("/products/{lcl}",[ProductController::class,'getProducts']);
    Route::get("/products/{lcl}/{id}",[ProductController::class,'getProduct']);
    Route::get('/brands',[BrandController::class,'getBrandList']);
    Route::get('/mainpage',[MainPageController::class,'mainPageData']);

});
