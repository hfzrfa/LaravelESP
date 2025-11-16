<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DhtReadingController;

Route::post('/dht', [DhtReadingController::class, 'store']);
