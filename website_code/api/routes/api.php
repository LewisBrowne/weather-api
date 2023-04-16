<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLocationController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\WeatherController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(UserController::class)->group(function () {
    Route::post('/user/login', 'login');
    Route::post('/user/register', 'register');
});

Route::controller(CityController::class)->group(function () {
    Route::get('/city/{query}', 'search');
    Route::get('/city', 'list');
});

Route::controller(WeatherController::class)->group(function () {
    Route::get('/weather/now/city/{guid}', 'cityWeatherNow');
    Route::get('/weather/history/city/{guid}', 'cityWeatherHistory');
    Route::get('/weather/now/coord', 'coordWeatherNow');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::put('/user/dailyforecast/', 'DailyForecastSubscribeUnsubscribe');
    });

    Route::controller(UserLocationController::class)->group(function () {
        Route::post('/user/location/', 'store');
        Route::get('/user/location', 'list');
        Route::delete('/user/location/{guid}', 'delete');
        Route::get('/user/location/{guid}', 'getLocation');
    });
    //Route::post('/user/test', [UserController::class, 'test']);
});