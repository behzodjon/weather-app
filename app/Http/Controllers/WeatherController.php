<?php

namespace App\Http\Controllers;

use App\Http\Requests\PullWeatherDataRequest;
use App\Jobs\PullWeatherApiData;
use Illuminate\Http\Request;
use App\Models\WeatherForecast;

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
