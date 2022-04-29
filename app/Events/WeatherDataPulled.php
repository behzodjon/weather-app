<?php

namespace App\Events;

use App\Models\City;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WeatherDataPulled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $city;
    public $date;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(City $city, $date, $data)
    {
        $this->city = $city;
        $this->date = $date;
        $this->data = $data;
    }
}
