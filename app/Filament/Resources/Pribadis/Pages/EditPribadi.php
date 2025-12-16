<?php

namespace App\Filament\Resources\Pribadis\Pages;

use App\Filament\Resources\Pribadis\PribadiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPribadi extends EditRecord
{
    protected static string $resource = PribadiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
