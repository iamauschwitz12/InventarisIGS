<?php

namespace App\Filament\Resources\Gedungs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class GedungForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_gedung')
                    ->required(),
                TextInput::make('alamat')
                    ->required(),
            ]);
    }
}
