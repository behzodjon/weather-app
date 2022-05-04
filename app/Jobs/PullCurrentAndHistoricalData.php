<?php

namespace App\Jobs;

use App\Models\City;
use Illuminate\Bus\Queueable;
use App\Events\WeatherDataPulled;
use App\Services\OpenWeatherService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PullCurrentAndHistoricalData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        $this->date = $date;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OpenWeatherService $weather)
    {
        City::all()->each(function ($city) use ($weather) {

            $response = Cache::remember('openWeather' . $this->date . $city->lat, now()->addMinutes(10), function () use ($city, $weather) {
                return $weather->getHistoricalWeather($city->lat, $city->lng, $this->date);
            });


            if (!$response->successful()) {
                throw new \Exception('Not found', 404);
            }

            WeatherDataPulled::dispatch($city, $this->date, $response['current']);
        });
    }
}
