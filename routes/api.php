<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/weather/store', [WeatherController::class, 'store']);
