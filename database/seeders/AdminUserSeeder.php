<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@esp32.test'],
            [
                'name' => 'Admin ESP32',
                'password' => Hash::make('password'),
            ]
        );
    }
}
