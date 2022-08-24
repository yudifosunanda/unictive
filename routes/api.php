<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\PajakController;

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

//route CRUD Item
//pajak
Route::get('/pajak/get',[PajakController::class,'getdata']);
Route::post('/pajak/create',[PajakController::class,'create']);
Route::post('/pajak/update/{id}',[PajakController::class,'update']);
Route::delete('/pajak/delete/{id}',[PajakController::class,'delete']);

//item
Route::post('/item/create',[ItemController::class,'create']);
Route::post('/item/update/{id}',[ItemController::class,'update']);
Route::delete('/item/delete/{id}',[ItemController::class,'delete']);

// list data item
Route::get('/item/get',[ItemController::class,'getdata']);
