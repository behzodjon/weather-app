<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\WeatherForecast;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOrCreateWeatherTable
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        WeatherForecast::updateOrCreate(
            [
                'date' => Carbon::createFromTimestamp($event->date)->format('Y-m-d'),
                'city_id' => $event->city->id,
            ],
            [
                'data' => $event->data,
            ]
        );
    }
}
