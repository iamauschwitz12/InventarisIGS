<?php

namespace App\Filament\Resources\TipeAsetKategoris\Pages;

use App\Filament\Resources\TipeAsetKategoris\TipeAsetKategoriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipeAsetKategoris extends ListRecords
{
    protected static string $resource = TipeAsetKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
