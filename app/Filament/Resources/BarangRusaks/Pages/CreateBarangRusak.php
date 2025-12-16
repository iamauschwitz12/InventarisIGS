<?php

namespace App\Filament\Resources\BarangRusaks\Pages;

use App\Filament\Resources\BarangRusaks\BarangRusakResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Pribadi;

class CreateBarangRusak extends CreateRecord
{
    protected static string $resource = BarangRusakResource::class;
    protected function afterCreate(): void
    {
        $barangRusak = $this->record;

        $item = $barangRusak->inventaris;

        if ($item && isset($item->jumlah)) {
            $item->decrement('jumlah', $barangRusak->jumlah_rusak);
        }
    }
}
