<?php

namespace App\Filament\Resources\BarangRusaks\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ImageUpload;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Support\RawJs;
use App\Models\Pribadi;
use App\Models\DanaBos;
use App\Models\Sekolah;

class BarangRusakForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Pilih Sumber Inventaris
                Select::make('inventaris_type')
                ->label('Sumber Inventaris')
                ->options([
                Pribadi::class => 'Inventaris Pribadi',
                Sekolah::class => 'Inventaris Sekolah',
                DanaBos::class => 'Inventaris Dana BOS',
                ])
                ->reactive()
                ->required(),
                Select::make('inventaris_id')
                ->label('Nama Barang')
                ->options(fn ($get) => $get('inventaris_type') ? $get('inventaris_type')::pluck('nama_barang', 'id') : [])
                ->reactive()
                ->searchable()
                ->required(),
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
                Select::make('tipe_aset_id')
                ->relationship('tipeAset', 'tipe_aset')
                ->searchable()
                ->preload()
                ->required(),
                // Pilih Barang sesuai tabel
                TextInput::make('jumlah_rusak')
                ->label('Jumlah Rusak')
                ->numeric()
                ->minValue(1)
                ->required()
                ->rule(function ($get) {
                return function (string $attribute, $value, $fail) use ($get) {
                $model = $get('inventaris_type');
                $id = $get('inventaris_id');
                if ($model && $id) {
                $stok = $model::find($id)?->jumlah ?? 0;
                if ($value > $stok) {
                $fail("Jumlah rusak melebihi stok tersedia: $stok");
                }
                }
                };
                }),

                DatePicker::make('tgl_rusak')
                ->label('Tanggal Rusak')
                ->required(),
                DatePicker::make('tgl_input')
                ->label('Tanggal Input')
                ->required(),

                Textarea::make('keterangan')
                ->label('Keterangan')
                ->columnSpanFull(),
            ]);
    }
}
