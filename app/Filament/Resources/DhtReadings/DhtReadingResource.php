<?php

namespace App\Filament\Resources\DhtReadings;

use BackedEnum;
use App\Models\DhtReading;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\DhtReadings\Pages\EditDhtReading;
use App\Filament\Resources\DhtReadings\Pages\ListDhtReadings;
use App\Filament\Resources\DhtReadings\Pages\CreateDhtReading;
use App\Filament\Resources\DhtReadings\Schemas\DhtReadingForm;
use App\Filament\Resources\DhtReadings\Tables\DhtReadingsTable;

class DhtReadingResource extends Resource
{
    protected static ?string $model = DhtReading::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'DhtReading';

    public static function form(Schema $schema): Schema
    {
        return DhtReadingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('device_id')
                    ->label('Device')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('temperature')
                    ->label('Temp (°C)')
                    ->sortable(),

                TextColumn::make('humidity')
                    ->label('Humidity (%)')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d-m-Y H:i:s')
                    ->sortable()
                    ->since(), // tampil "2 minutes ago" di tooltip
            ])
            ->filters([
                //
            ])
            ->actions([
                // buat sekarang kita matikan edit/delete supaya pure monitoring
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDhtReadings::route('/'),
        ];
    }
}
