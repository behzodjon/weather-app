<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\WeatherForecast;
use App\Services\OpenWeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function index(Request $request, OpenWeatherService $weather)
    {
        dd(WeatherForecast::where('date', Carbon::today())->exists());
        $date=Carbon::tomorrow()->format('Y-m-d');
        dd( Carbon::createFromFormat('Y-m-d', $date)->isFuture());
        $date = Carbon::today();
        $cities = City::all();
        foreach ($cities as $city) {
            WeatherForecast::updateOrCreate(
                [
                    'date' => $date,
                    'city_id' => $city->id,
                ],
                [
                    'data' => [],
                ]
            );
        }

        dd(WeatherForecast::where('date', Carbon::today())->exists());
        // dd(Carbon::createFromDate($request->date)->timestamp);
        $today = Carbon::createFromTimestamp(1651230000)->format('m/d/Y');
        $tomorrow = Carbon::today()->format('m/d/Y');

        // dd(
        //     $today
        // );

        $city = City::first();

        $current_timestamp = Carbon::tomorrow()->timestamp;
        $response = Http::get('https://api.openweathermap.org/data/2.5/onecall?appid=ec18c2c7c5c242ab9f56cfa091350fd3&lat=' . $city->lat . '&lon=' . $city->lng . '&exclude=minutely,alerts');
        $days = collect($response->json()['daily']);
        // dd($days);
        $exactDay = $days->filter(function ($value) use ($current_timestamp) {
            return Carbon::createFromTimestamp($value['dt'])->format('m/d/Y') == Carbon::createFromTimestamp($current_timestamp)->format('m/d/Y');
        });
        dd(collect($exactDay)->first());
    }
}
