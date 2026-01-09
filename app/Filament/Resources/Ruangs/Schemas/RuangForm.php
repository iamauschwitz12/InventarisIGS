<?php

namespace App\Filament\Resources\Ruangs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class RuangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lantai_id')
                ->label('Lantai')
                ->relationship('lantai', 'lantai')
                ->required(),

                TextInput::make('ruang')
                    ->required(),
            ]);
    }
}
