<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Jobs\PullHistoricalWeatherData;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Jobs\PullCurrentAndForecastWeatherData;

class PullWeather implements ShouldQueue
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
    public function handle()
    {
        $dateTimestamp = Carbon::createFromDate($this->date)->timestamp;

        Carbon::createFromDate($this->date)->isPast()
            ? PullHistoricalWeatherData::dispatch($dateTimestamp)
            : PullCurrentAndForecastWeatherData::dispatch($dateTimestamp);
    }
}
