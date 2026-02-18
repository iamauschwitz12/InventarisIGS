<?php

namespace App\Filament\Resources\Pribadis\Pages;

use App\Filament\Resources\Pribadis\PribadiResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\Pribadi;

class CreatePribadi extends CreateRecord
{
    protected static string $resource = PribadiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $jumlah = max(1, (int) ($data['jumlah'] ?? 1));
        $baseKode = $data['kode_inventaris'];
        $groupId = (string) Str::uuid();

        // Cari nomor urut terakhir yang sudah ada dengan base kode ini
        $lastRecord = Pribadi::where('kode_inventaris', 'like', $baseKode . '-%')
            ->orderByRaw("CAST(SUBSTRING_INDEX(kode_inventaris, '-', -1) AS UNSIGNED) DESC")
            ->first();

        $startNumber = 1;
        if ($lastRecord) {
            $lastKode = $lastRecord->kode_inventaris;
            $lastNumber = (int) substr($lastKode, strrpos($lastKode, '-') + 1);
            $startNumber = $lastNumber + 1;
        }

        $firstRecord = null;

        for ($i = 0; $i < $jumlah; $i++) {
            $sequenceNumber = $startNumber + $i;
            $kodeInventaris = $baseKode . '-' . str_pad($sequenceNumber, 5, '0', STR_PAD_LEFT);

            $recordData = $data;
            $recordData['kode_inventaris'] = $kodeInventaris;
            $recordData['jumlah'] = 1;
            $recordData['group_id'] = $groupId;

            $record = Pribadi::create($recordData);

            if ($i === 0) {
                $firstRecord = $record;
            }
        }

        return $firstRecord;
    }
}
