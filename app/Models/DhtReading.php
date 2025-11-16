<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DhtReading extends Model
{
    protected $fillable = [
        'device_id',
        'temperature',
        'humidity',
    ];
}
