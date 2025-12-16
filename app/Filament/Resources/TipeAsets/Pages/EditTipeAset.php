<?php

namespace App\Filament\Resources\TipeAsets\Pages;

use App\Filament\Resources\TipeAsets\TipeAsetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipeAset extends EditRecord
{
    protected static string $resource = TipeAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
