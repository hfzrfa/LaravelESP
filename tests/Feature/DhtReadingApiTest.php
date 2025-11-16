<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores a reading when the secret matches', function () {
    config(['services.esp32.secret' => 'test-secret']); // Ensure request and config use same shared secret

    $payload = [
        'device_id' => 'esp32-lab',
        'temperature' => 24.5,
        'humidity' => 60.3,
        'secret' => 'test-secret',
    ];

    $response = $this->postJson('/api/dht', $payload);

    $response->assertStatus(200)
        ->assertJson(['message' => 'OK']);

    $this->assertDatabaseHas('dht_readings', [
        'device_id' => 'esp32-lab',
        'temperature' => 24.5,
        'humidity' => 60.3,
    ]);
});

it('rejects the request when the secret is wrong', function () {
    config(['services.esp32.secret' => 'test-secret']);

    $response = $this->postJson('/api/dht', [
        'temperature' => 24.5,
        'humidity' => 60.3,
        'secret' => 'bad-secret',
    ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthorized']);
});
