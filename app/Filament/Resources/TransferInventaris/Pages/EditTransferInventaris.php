<?php

namespace App\Filament\Resources\TransferInventaris\Pages;

use App\Filament\Resources\TransferInventaris\TransferInventarisResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransferInventaris extends EditRecord
{
    protected static string $resource = TransferInventarisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
