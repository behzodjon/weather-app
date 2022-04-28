<?php

namespace App\Jobs;

use App\Models\City;
use Illuminate\Bus\Queueable;
use App\Models\WeatherForecast;
use App\Services\OpenWeatherService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PullHistoricalWeatherData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $lat;
    protected $lng;
    protected $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lat, $lng, $date)
    {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OpenWeatherService $weather)
    {
        $response = $weather->getHistoricalWeather($this->lat, $this->lng, $this->date);
        $cities = City::all();
        foreach ($cities as $city) {
            WeatherForecast::create([
                'city_id' => $city->id,
                'date' => $this->date,
                'data' => $response,
            ]);
        }
    }
}
