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
use App\Models\TipeAsetDanaBos;

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
                    Pribadi::class  => 'Inventaris Pribadi',
                    Sekolah::class  => 'Inventaris Sekolah',
                    DanaBos::class  => 'Inventaris Dana BOS',
                ])
                ->reactive()
                ->afterStateUpdated(fn ($set) => $set('inventaris_id', null))
                ->required(),
                Select::make('tipe_aset_id')
                ->label('Tipe Aset')
                ->options(function ($get) {
                    // Hanya tampilkan untuk Pribadi dan Sekolah
                    $inventaris_type = $get('inventaris_type');
                    
                    if ($inventaris_type === DanaBos::class) {
                        return [];
                    }
                    
                    return \App\Models\TipeAset::query()
                        ->pluck('tipe_aset', 'id');
                })
                ->visible(fn ($get) => $get('inventaris_type') !== DanaBos::class)
                ->reactive()

                ->searchable()
                ->preload()
                ->required(fn ($get) => $get('inventaris_type') !== DanaBos::class)
                ->required(),
                Select::make('lantai_id')
                ->label('Lantai')
                ->relationship('lantai', 'lantai')
                ->reactive()

                ->searchable()
                ->preload()
                ->required(),
                Select::make('ruang_id')
                ->label('Ruang')
                ->options(fn ($get) =>
                    \App\Models\Ruang::query()
                        ->where('lantai_id', $get('lantai_id'))
                        ->pluck('ruang', 'id')
                )
                ->reactive()
                ->searchable()
                ->required(),
                Select::make('inventaris_id')
                ->label('Nama Barang')
                ->options(function ($get) {
                    $model = $get('inventaris_type');

                    if (! $model) {
                        return [];
                    }

                    $query = $model::query();
                    
                    // Untuk DanaBos, filter dengan tipe_aset_dana_bos_id
                    if ($model === DanaBos::class) {
                        $query = $query->when($get('tipe_aset_id'), fn ($q) =>
                            $q->where('tipe_aset_dana_bos_id', $get('tipe_aset_id'))
                        );
                    } else {
                        // Untuk Pribadi dan Sekolah, filter dengan tipe_aset_id
                        $query = $query->when($get('tipe_aset_id'), fn ($q) =>
                            $q->where('tipe_aset_id', $get('tipe_aset_id'))
                        );
                    }

                    return $query
                        ->when($get('lantai_id'), fn ($q) =>
                            $q->where('lantai_id', $get('lantai_id'))
                        )
                        ->when($get('ruang_id'), fn ($q) =>
                            $q->where('ruang_id', $get('ruang_id'))
                        )
                        ->pluck('nama_barang', 'id');
                })
                ->reactive()
                ->afterStateUpdated(function ($state, $set, $get) {
                    if ($state) {
                        $model = $get('inventaris_type');
                        if ($model) {
                            $inventaris = $model::find($state);
                            $set('no_seri', $inventaris?->no_seri);
                            // Set tipe_aset_id dari inventaris hanya untuk Pribadi/Sekolah
                            if ($model !== DanaBos::class) {
                                $set('tipe_aset_id', $inventaris?->tipe_aset_id);
                            }
                        }
                    }
                })
                ->searchable()
                ->required(),
                TextInput::make('no_seri')
                ->label('Nomor Seri')
                ->disabled()
                ->dehydrated(),
                // Pilih Barang sesuai tabel
                TextInput::make('jumlah_rusak')
                ->label('Jumlah Rusak')
                ->numeric()
                ->minValue(1)
                ->required()
                ->rule(fn ($get) => function ($attribute, $value, $fail) use ($get) {
                    $model = $get('inventaris_type');
                    $id    = $get('inventaris_id');

                    if ($model && $id) {
                        $stok = $model::find($id)?->jumlah ?? 0;

                        if ($value > $stok) {
                            $fail("Jumlah rusak melebihi stok tersedia: $stok");
                        }
                    }
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
