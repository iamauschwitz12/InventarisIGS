<?php

namespace App\Filament\Resources\TransferInventaris\Pages;

use App\Filament\Resources\TransferInventaris\TransferInventarisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransferInventaris extends ListRecords
{
    protected static string $resource = TransferInventarisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
