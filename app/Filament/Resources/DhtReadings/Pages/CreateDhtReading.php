<?php

namespace App\Filament\Resources\DhtReadings\Pages;

use App\Filament\Resources\DhtReadings\DhtReadingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDhtReading extends CreateRecord
{
    protected static string $resource = DhtReadingResource::class;
}
