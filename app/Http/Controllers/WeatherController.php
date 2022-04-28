<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\WeatherForecast;
use App\Services\OpenWeatherService;

class WeatherController extends Controller
{
    public function index()
    {
        # code...
    }

    public function store(Request $request, OpenWeatherService $weather)
    {

        $current_timestamp = Carbon::createFromDate($request->date)->timestamp;
        if (WeatherForecast::where('date', $request->date)->doesntExist()) {
            $cities = City::all();
            foreach ($cities as $city) {

                $response = Carbon::createFromDate($request->date)->isPast()
                    ? $weather->getHistoricalWeather($city->lat, $city->lng, $current_timestamp)
                    : $weather->getCurrentAndForecastWeather($city->lat, $city->lng, $current_timestamp);
                if ($response->successful()) {
                    WeatherForecast::create([
                        'city_id' => $city->id,
                        'date' => $current_timestamp,
                        'data' => $response,
                    ]);
                } else {
                    return response()->json([
                        'code' => $response->json()['cod'],
                        'message' => "Not Found"
                    ]);
                }
            }
        }


        return response()->noContent();
    }

    public function update()
    {
        # code...
    }
}
