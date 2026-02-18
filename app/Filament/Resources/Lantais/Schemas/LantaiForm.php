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
                \Filament\Forms\Components\Select::make('gedung_id')
                    ->label('Gedung')
                    ->relationship('gedung', 'nama_gedung')
                    ->required(),
                TextInput::make('lantai')
                    ->label('Nama Lantai')
                    ->placeholder('Masukan lantai : L6/L7/L8')
                    ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, callable $get) {
                        return $rule->where('gedung_id', $get('gedung_id'));
                    })
                    ->required(),
            ]);
    }
}
