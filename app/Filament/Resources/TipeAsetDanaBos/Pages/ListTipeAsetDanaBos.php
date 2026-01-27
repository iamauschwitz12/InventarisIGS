<?php

namespace App\Filament\Resources\TipeAsetDanaBos\Pages;

use App\Filament\Resources\TipeAsetDanaBos\TipeAsetDanaBosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipeAsetDanaBos extends ListRecords
{
    protected static string $resource = TipeAsetDanaBosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
