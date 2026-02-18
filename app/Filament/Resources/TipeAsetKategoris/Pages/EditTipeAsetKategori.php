<?php

namespace App\Filament\Resources\TipeAsetKategoris\Pages;

use App\Filament\Resources\TipeAsetKategoris\TipeAsetKategoriResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipeAsetKategori extends EditRecord
{
    protected static string $resource = TipeAsetKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
