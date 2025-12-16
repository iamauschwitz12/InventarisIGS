<?php

namespace App\Filament\Resources\DanaBos\Pages;

use App\Filament\Resources\DanaBos\DanaBosResource;
use Filament\Resources\Pages\CreateRecord;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateDanaBos extends CreateRecord
{
    protected static string $resource = DanaBosResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Ambil semua field record dalam bentuk array
        $data = $record->toArray();

        // Convert ke JSON agar ketika discan muncul data lengkap
        $qrContent = "Inventaris Dana Bos\nNama Barang: {$record->nama_barang}\nRuangan: {$record->ruang}\nLantai: {$record->lantai}\nJumlah: {$record->jumlah}";

        // Generate QR Code
        $qr = QrCode::format('png')
            ->size(300)
            ->generate($qrContent);

        // Simpan file
        $fileName = 'qrcodes/' . Str::uuid() . '.png';
        Storage::disk('public')->put($fileName, $qr);

        // Update kolom qrcode
        $record->qrcode = $fileName;
        $record->save();
        }
}
