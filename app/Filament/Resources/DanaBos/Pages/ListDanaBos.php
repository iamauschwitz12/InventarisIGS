<?php

namespace App\Filament\Resources\DanaBos\Pages;

use App\Filament\Resources\DanaBos\DanaBosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDanaBos extends ListRecords
{
    protected static string $resource = DanaBosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
