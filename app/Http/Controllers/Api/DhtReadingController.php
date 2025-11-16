<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DhtReading;
use Illuminate\Http\Request;

class DhtReadingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'device_id'   => 'nullable|string',
            'temperature' => 'required|numeric',
            'humidity'    => 'required|numeric',
            'secret'      => 'required|string',
        ]);

        if ($data['secret'] !== config('services.esp32.secret')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $reading = DhtReading::create([
            'device_id'   => $data['device_id'] ?? 'esp32-1',
            'temperature' => $data['temperature'],
            'humidity'    => $data['humidity'],
        ]);

        return response()->json([
            'message' => 'OK',
            'id'      => $reading->id,
        ]);
    }
}
