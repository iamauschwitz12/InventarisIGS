<?php

namespace App\Filament\Resources\DanaBos\Pages;

use App\Filament\Resources\DanaBos\DanaBosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDanaBos extends EditRecord
{
    protected static string $resource = DanaBosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
