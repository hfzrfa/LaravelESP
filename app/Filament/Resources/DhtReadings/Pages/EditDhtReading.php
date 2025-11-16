<?php

namespace App\Filament\Resources\DhtReadings\Pages;

use App\Filament\Resources\DhtReadings\DhtReadingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDhtReading extends EditRecord
{
    protected static string $resource = DhtReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
