<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherForecast extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'data', 'city_id'];

    protected $casts = [
        'date' => 'immutable_date',
        'data' => 'array',
    ];
}
