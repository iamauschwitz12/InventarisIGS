<?php

namespace App\Filament\Resources\Pribadis\Pages;

use App\Filament\Resources\Pribadis\PribadiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPribadis extends ListRecords
{
    protected static string $resource = PribadiResource::class;

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