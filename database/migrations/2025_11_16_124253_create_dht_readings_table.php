<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dht_readings', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->default('esp32-1');
            $table->float('temperature'); // Celsius
            $table->float('humidity');    // %
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dht_readings');
    }
};
