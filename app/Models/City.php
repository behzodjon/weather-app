<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable=['lat','lng'];

    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
    ];
}
