<?php

namespace App\Filament\Resources\Ruangs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class RuangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ruang')
                ->placeholder('Masukan ruang : 601/602/603')
                ->unique(ignoreRecord: true)
                ->required(),
            ]);
    }
}
