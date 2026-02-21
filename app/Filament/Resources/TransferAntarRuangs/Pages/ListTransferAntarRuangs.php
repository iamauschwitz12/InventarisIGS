<?php

namespace App\Filament\Resources\TransferAntarRuangs\Pages;

use App\Filament\Resources\TransferAntarRuangs\TransferAntarRuangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransferAntarRuangs extends ListRecords
{
    protected static string $resource = TransferAntarRuangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
