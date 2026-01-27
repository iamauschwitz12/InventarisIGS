<?php

namespace App\Filament\Resources\Engineerings\Pages;

use App\Filament\Resources\Engineerings\EngineeringResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEngineerings extends ListRecords
{
    protected static string $resource = EngineeringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
