<?php

namespace App\Filament\Resources\TransferAntarRuangs\Pages;

use App\Filament\Resources\TransferAntarRuangs\TransferAntarRuangResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransferAntarRuang extends CreateRecord
{
    protected static string $resource = TransferAntarRuangResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
