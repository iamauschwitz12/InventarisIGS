<?php

namespace App\Filament\Resources\Gedungs\Pages;

use App\Filament\Resources\Gedungs\GedungResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGedungs extends ListRecords
{
    protected static string $resource = GedungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
