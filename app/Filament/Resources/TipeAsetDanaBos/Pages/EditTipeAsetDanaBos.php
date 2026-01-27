<?php

namespace App\Filament\Resources\TipeAsetDanaBos\Pages;

use App\Filament\Resources\TipeAsetDanaBos\TipeAsetDanaBosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipeAsetDanaBos extends EditRecord
{
    protected static string $resource = TipeAsetDanaBosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
