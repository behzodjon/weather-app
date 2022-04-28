<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            [
                'title'=>'London',
                'lat'=>51.509865,
                'lng'=>-0.118092,
            ],
            [
                'title'=>'New York',
                'lat'=>40.730610,
                'lng'=>-73.935242,
            ],
            [
                'title'=>'Paris',
                'lat'=>48.864716,
                'lng'=>2.349014,
            ],
            [
                'title'=>'Berlin',
                'lat'=>52.520008,
                'lng'=>13.404954,
            ],
            [
                'title'=>'Tokyo',
                'lat'=>35.652832,
                'lng'=>139.839478,
            ],
            
        ];

        foreach ($cities as $city) {
            City::updateOrCreate([
                'title' => $city['title'],
                'lat' => $city['lat'],
                'lng' => $city['lng'],
            ], []);
        }
    }
}
