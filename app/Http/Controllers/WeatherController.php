<?php

namespace App\Http\Controllers;

use App\Jobs\PullCurrentAndForecastWeatherData;
use App\Jobs\PullHistoricalWeatherData;
use App\Jobs\PullWeather;
use Carbon\Carbon;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\WeatherForecast;
use App\Services\OpenWeatherService;

class WeatherController extends Controller
{
  
    public function store(Request $request)
    {
        if (WeatherForecast::where('date', $request->date)->doesntExist()) {
            PullWeather::dispatch($request->date);
        }

        return response()->noContent();
    }

}
