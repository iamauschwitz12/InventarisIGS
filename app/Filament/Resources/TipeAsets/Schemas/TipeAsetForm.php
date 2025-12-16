<?php

namespace App\Filament\Resources\TipeAsets\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class TipeAsetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tipe_aset')
                ->placeholder('Masukan tipe aset : Elektronik/Furniture/AC')
                ->unique(ignoreRecord: true)
                ->required(),
            ]);
    }
}
