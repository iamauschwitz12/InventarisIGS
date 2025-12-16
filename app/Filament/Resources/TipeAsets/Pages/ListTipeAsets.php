<?php

namespace App\Filament\Resources\TipeAsets\Pages;

use App\Filament\Resources\TipeAsets\TipeAsetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipeAsets extends ListRecords
{
    protected static string $resource = TipeAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
