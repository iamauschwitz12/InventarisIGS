<?php

namespace App\Filament\Resources\TipeAsetDanaBos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class TipeAsetDanaBosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tipe_aset_dana_bos')
                ->placeholder('Masukan tipe aset : A/B/C/D')
                ->unique(ignoreRecord: true)
                ->required(),
            ]);
    }
}
