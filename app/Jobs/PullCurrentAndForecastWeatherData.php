<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\City;
use Illuminate\Bus\Queueable;
use App\Models\WeatherForecast;
use App\Events\WeatherDataPulled;
use App\Services\OpenWeatherService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PullCurrentAndForecastWeatherData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $date;

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
                return $weather->getCurrentAndForecastWeather($city->lat, $city->lng);
            });

            $exactDay = collect($response->json()['daily'])->filter(function ($value) {
                return Carbon::createFromTimestamp($value['dt'])->format('m/d/Y') == Carbon::createFromTimestamp($this->date)->format('m/d/Y');
            });

            $data = collect($exactDay)->first();

            if (!$data) {
                throw new \Exception('Not found', 404);
            }

            WeatherDataPulled::dispatch($city, $this->date, $data);
        });
    }
}
