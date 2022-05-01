<?php

namespace Tests\Unit;

use App\Jobs\PullWeather;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;


class WeatheApiTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function test_pull_weather_can_be_dispatched()
    {
        Queue::fake();
        
        // Push a job
        PullWeather::dispatch('2022-03-30');
        
        // Assert the job was pushed to the queue
        Queue::assertPushed(PullWeather::class);

    }
}
