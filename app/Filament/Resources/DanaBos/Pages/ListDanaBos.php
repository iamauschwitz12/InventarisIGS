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

    public function deletePrintHistory($id)
    {
        $record = \App\Models\PrintHistory::find($id);
        if ($record) {
            $record->delete();
            \Filament\Notifications\Notification::make()
                ->title('Berhasil dihapus')
                ->success()
                ->send();
        }
    }
}
