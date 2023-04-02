<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MomController;
use App\Http\Controllers\QuestionController;
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


Route::group(['middleware'=>'api'],function(){

    Route::get('fetch_auth_info',[AuthController::class,'fetch_auth_info']);
    Route::post('login',[AuthController::class,'login']);

});
Route::middleware(['jwt.verify'])->group(function(){

    Route::get('get_quiz/{topics}',[ApiController::class,'get_quiz']);
    Route::get('refresh_token',[AuthController::class,'refresh_token']);
    Route::get('logout',[AuthController::class,'logout']);

    Route::post('submit_answer',[ApiController::class,'submit_answer']);


});

