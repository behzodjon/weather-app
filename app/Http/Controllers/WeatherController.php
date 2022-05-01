<?php

namespace App\Http\Controllers;

use App\Http\Requests\PullWeatherDataRequest;
use App\Jobs\PullCurrentAndForecastWeatherData;
use App\Jobs\PullHistoricalWeatherData;
use App\Jobs\PullWeather;
use App\Jobs\PullWeatherApiData;
use Carbon\Carbon;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\WeatherForecast;
use App\Services\OpenWeatherService;

class WeatherController extends Controller
{

    public function store(PullWeatherDataRequest $request)
    {
        $validatedData = $request->validated();

        if (WeatherForecast::whereDate('date', $validatedData)->doesntExist()) {
            PullWeatherApiData::dispatch($request->date);
        }

        return response()->noContent();
    }
}
