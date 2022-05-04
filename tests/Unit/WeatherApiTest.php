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
use Illuminate\Support\Facades\Queue;
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
        Bus::fake();

        $date = Carbon::yesterday();

        PullCurrentAndHistoricalData::dispatch($date);

        Bus::assertDispatched(function (PullCurrentAndHistoricalData $job) {
            return Carbon::createFromDate($job->date)->isPast();
        });

        Bus::assertNotDispatched(PullForecastData::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_dispatch_forecast_job()
    {
        Bus::fake();

        $date = Carbon::tomorrow();

        PullForecastData::dispatch($date);

        Bus::assertDispatched(function (PullForecastData $job) {
            return Carbon::createFromDate($job->date)->isFuture();
        });

        Bus::assertNotDispatched(PullCurrentAndHistoricalData::class);
    }

      /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_dispatch_weather_data_pulled_event()
    {
        Event::fake();
        
        $city = City::factory()->create();

        $date = Carbon::tomorrow();

        WeatherDataPulled::dispatch($city, $date, []);

        Event::assertDispatched(WeatherDataPulled::class);

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
