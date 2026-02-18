<?php

namespace App\Filament\Resources\TipeAsetKategoris\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TipeAsetKategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tipe_aset_id')
                    ->label('Tipe Aset')
                    ->relationship('tipeAset', 'tipe_aset')
                    ->required(),
                TextInput::make('nama_tipe_kategori')
                    ->label('Nama Tipe Kategori')
                    ->required(),
            ]);
    }
}
