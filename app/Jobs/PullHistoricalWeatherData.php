<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\City;
use Illuminate\Bus\Queueable;
use App\Models\WeatherForecast;
use App\Services\OpenWeatherService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsUnprocessable;

class PullHistoricalWeatherData implements ShouldQueue
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
        $cities = City::all();
        info($this->date);

        foreach ($cities as $city) {
            $response = $weather->getHistoricalWeather($city->lat, $city->lng, $this->date);
            if (!$response->successful()) {
                throw new \Exception($response->json()['message'], $response->json()['cod'],);
            }
            WeatherForecast::updateOrCreate(
                [
                    'date' => Carbon::createFromTimestamp($this->date)->format('Y-m-d'),
                    'city_id' => $city->id,
                ],
                [
                    'data' => $response['current'],
                ]
            );
        }
    }
}
