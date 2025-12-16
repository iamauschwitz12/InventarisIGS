<?php

namespace App\Filament\Resources\Lantais\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class LantaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('lantai')
                ->placeholder('Masukan lantai : L6/L7/L8')
                ->unique(ignoreRecord: true)
                ->required(),
            ]);
    }
}
