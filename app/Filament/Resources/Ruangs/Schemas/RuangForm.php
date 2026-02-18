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
                Select::make('gedung_id')
                    ->label('Gedung')
                    ->relationship('gedung', 'nama_gedung')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn(callable $set) => $set('lantai_id', null)),

                Select::make('lantai_id')
                    ->label('Lantai')
                    ->relationship('lantai', 'lantai', function ($query, callable $get) {
                        $gedungId = $get('gedung_id');
                        if ($gedungId) {
                            $query->where('gedung_id', $gedungId);
                        }
                    })
                    ->required(),

                TextInput::make('ruang')
                    ->required()
                    ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, callable $get) {
                        return $rule->where('gedung_id', $get('gedung_id'));
                    }),
            ]);
    }
}
