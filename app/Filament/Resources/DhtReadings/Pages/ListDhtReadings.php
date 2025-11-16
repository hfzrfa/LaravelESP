<?php

namespace App\Filament\Resources\DhtReadings\Pages;

use App\Filament\Resources\DhtReadings\DhtReadingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDhtReadings extends ListRecords
{
    protected static string $resource = DhtReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
