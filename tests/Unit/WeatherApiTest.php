<?php

namespace Tests\Unit;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use App\Jobs\PullWeatherApiData;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class WeatherApiTest extends TestCase
{

    use DispatchesJobs;
   
    
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        Queue::fake();
        
        $job = new PullWeatherApiData(Carbon::now());
        $job->dispatch();
        $job->handle();

        // Queue::assertPushed(SecondJob::class,  function ($job) use ($michael) {
        //     return $job->manager->id === $michael->id;
        // });

        $this->assertTrue(true);

    }
}
