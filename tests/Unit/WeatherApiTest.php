<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\City;
use App\Jobs\PullForecastData;
use App\Jobs\PullWeatherApiData;
use App\Events\WeatherDataPulled;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;
use App\Jobs\PullCurrentAndHistoricalData;
use App\Listeners\UpdateOrCreateWeatherTable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeatherApiTest extends TestCase
{

    use DispatchesJobs;
    use RefreshDatabase;


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_dispatch_current_and_historical_job()
    {
        Bus::fake([PullCurrentAndHistoricalData::class]);

        $now = Carbon::now()->subDay();

        PullWeatherApiData::dispatchSync($now);

        Bus::assertDispatched(PullCurrentAndHistoricalData::class, 1);
    }


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_dispatch_forecast_job()
    {
        Bus::fake([PullForecastData::class]);

        $now = Carbon::tomorrow();

        PullWeatherApiData::dispatchSync($now);

        Bus::assertDispatched(PullForecastData::class, 1);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_dispatch_weather_data_pulled_event()
    {
        Event::fake();


        $data =  Http::fake([
            'https://api.openweathermap.org/data/2.5/onecall/*' => Http::response(['current'], 200, ['Headers']),
        ]);

        $city = City::factory()->create();

        $date = Carbon::yesterday();

        event(new WeatherDataPulled($city, $date, $data));

        Event::assertDispatched(WeatherDataPulled::class, function ($e) use ($city) {
            return $e->city->id === $city->id;
        });

    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_is_attached_to_event()
    {
        Event::fake();

        Event::assertListening(
            WeatherDataPulled::class,
            UpdateOrCreateWeatherTable::class
        );
    }
}
