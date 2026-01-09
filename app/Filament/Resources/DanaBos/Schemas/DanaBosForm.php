<?php

namespace App\Filament\Resources\DanaBos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ImageUpload;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Support\RawJs;

class DanaBosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_barang')
                ->placeholder('Masukan nama barang')
                ->extraAttributes([
                    'oninput' => "this.value = this.value.toUpperCase()",
                ]),
                Select::make('lantai_id')
                ->relationship('lantai', 'lantai')
                ->searchable()
                ->preload()
                ->required(),
                Select::make('ruang_id')
                ->relationship('ruang', 'ruang')   // menampilkan nama ruang
                ->searchable()
                ->preload()
                ->required(),
                TextInput::make('harga')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->numeric(),
                DatePicker::make('tgl_beli')
                ->required(),
                Select::make('tipe_aset_id')
                ->relationship('tipeAset', 'tipe_aset')
                ->searchable()
                ->preload()
                ->required(),
                TextInput::make('jumlah')
                ->numeric(),
                TextInput::make('keterangan')
                ->label('Keterangan')
                ->placeholder('Input keterangan atau kosongkan'),
                FileUpload::make('img')
            ]);
    }
}
