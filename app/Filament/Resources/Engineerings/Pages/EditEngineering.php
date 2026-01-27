<?php

namespace App\Filament\Resources\Engineerings\Pages;

use App\Filament\Resources\Engineerings\EngineeringResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEngineering extends EditRecord
{
    protected static string $resource = EngineeringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
