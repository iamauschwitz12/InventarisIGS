<?php

namespace App\Filament\Resources\Gedungs\Pages;

use App\Filament\Resources\Gedungs\GedungResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGedung extends EditRecord
{
    protected static string $resource = GedungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
