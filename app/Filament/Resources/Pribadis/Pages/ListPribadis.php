<?php

namespace App\Filament\Resources\Pribadis\Pages;

use App\Filament\Resources\Pribadis\PribadiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPribadis extends ListRecords
{
    protected static string $resource = PribadiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}