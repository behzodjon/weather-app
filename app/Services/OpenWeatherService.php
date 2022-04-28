<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class OpenWeatherService
{

    protected $api_key;
    protected $endpoint;

    public function __construct()
    {
        $this->api_key = config('services.openweather.api_key');
        $this->endpoint = 'https://api.openweathermap.org/data/2.5/onecall';
    }

    /**
     * common method for call OpenWeatherMap API
     *
     * @param string $uri
     * @return json
     */
    public function callApi($uri)
    {
        return Http::get($this->endpoint . $uri);
    }

    public function getHistoricalWeather($lat, $lon, $date)
    {
        return $this->callApi('/timemachine?lat=' . $lat . '&lon=' . $lon . '&dt=' . $date . '&appid=' . $this->api_key);
     
    }

    public function getCurrentAndForecastWeather($lat, $lon, $date)
    {
        $response = $this->callApi('?lat=' . $lat . '&lon=' . $lon . '&exclude=current,minutely,hourly,alerts&appid=' . $this->api_key);

        if ($response->successful()) {

            $exactDay = collect($response->json()['daily'])->filter(function ($value) use ($date) {
                return Carbon::createFromTimestamp($value['dt'])->format('m/d/Y') == Carbon::createFromTimestamp($date)->format('m/d/Y');
            });

            return collect($exactDay)->first();
        } else {

            return response()->json([
                'code' => $response->json()['cod'],
                'message' => "Not Found"
            ]);
        }
    }
}
