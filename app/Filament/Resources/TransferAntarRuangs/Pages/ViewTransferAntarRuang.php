<?php

namespace App\Filament\Resources\TransferAntarRuangs\Pages;

use App\Filament\Resources\TransferAntarRuangs\TransferAntarRuangResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransferAntarRuang extends ViewRecord
{
    protected static string $resource = TransferAntarRuangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
