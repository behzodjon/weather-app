<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/test', [TestController::class, 'index']);
Route::get('/weather', [WeatherController::class, 'index']);
Route::post('/weather/store', [WeatherController::class, 'store']);
