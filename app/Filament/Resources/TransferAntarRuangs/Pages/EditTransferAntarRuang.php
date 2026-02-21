<?php

namespace App\Filament\Resources\TransferAntarRuangs\Pages;

use App\Filament\Resources\TransferAntarRuangs\TransferAntarRuangResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransferAntarRuang extends EditRecord
{
    protected static string $resource = TransferAntarRuangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
