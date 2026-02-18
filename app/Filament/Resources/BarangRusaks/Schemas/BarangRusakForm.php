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
                        Pribadi::class => 'Inventaris Pribadi',
                        Sekolah::class => 'Inventaris Sekolah',
                        DanaBos::class => 'Inventaris Dana BOS',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function ($set) {
                        $set('inventaris_id', null);
                        $set('tipe_aset_id', null);
                        $set('tipe_aset_dana_bos_id', null);
                        $set('gedung_id', null);
                        $set('lantai_id', null);
                        $set('ruang_id', null);
                        $set('no_seri', null);
                        $set('jumlah_rusak', 0);
                    })
                    ->required(),

                Select::make('tipe_aset_id')
                    ->label('Tipe Aset')
                    ->options(function ($get) {
                        $inventaris_type = $get('inventaris_type');
                        if ($inventaris_type === DanaBos::class) {
                            return [];
                        }
                        return \App\Models\TipeAset::query()
                            ->pluck('tipe_aset', 'id');
                    })
                    ->visible(fn($get) => $get('inventaris_type') && $get('inventaris_type') !== DanaBos::class)
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($set) {
                        $set('inventaris_id', null);
                        $set('jumlah_rusak', 0);
                    })
                    ->required(fn($get) => $get('inventaris_type') !== DanaBos::class),

                Select::make('tipe_aset_dana_bos_id')
                    ->label('Tipe Aset Dana BOS')
                    ->options(TipeAsetDanaBos::pluck('tipe_aset_dana_bos', 'id'))
                    ->visible(fn($get) => $get('inventaris_type') === DanaBos::class)
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($set) {
                        $set('inventaris_id', null);
                        $set('jumlah_rusak', 0);
                    })
                    ->dehydrated(false),

                Select::make('gedung_id')
                    ->label('Gedung')
                    ->options(\App\Models\Gedung::pluck('nama_gedung', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function ($set) {
                        $set('lantai_id', null);
                        $set('ruang_id', null);
                        $set('inventaris_id', null);
                        $set('jumlah_rusak', 0);
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('lantai_id')
                    ->label('Lantai')
                    ->options(function (callable $get) {
                        $gedungId = $get('gedung_id');
                        if (!$gedungId)
                            return [];
                        return \App\Models\Lantai::where('gedung_id', $gedungId)->pluck('lantai', 'id');
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($set) {
                        $set('ruang_id', null);
                        $set('inventaris_id', null);
                        $set('jumlah_rusak', 0);
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('ruang_id')
                    ->label('Ruang')
                    ->options(function (callable $get) {
                        $gedungId = $get('gedung_id');
                        $lantaiId = $get('lantai_id');
                        if (!$lantaiId)
                            return [];

                        $query = \App\Models\Ruang::where('lantai_id', $lantaiId);
                        if ($gedungId) {
                            $query->where('gedung_id', $gedungId);
                        }
                        return $query->pluck('ruang', 'id');
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($set) {
                        $set('inventaris_id', null);
                        $set('jumlah_rusak', 0);
                    })
                    ->searchable()
                    ->required(),

                Select::make('inventaris_id')
                    ->label('Pilih Barang')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->options(function ($get) {
                        $model = $get('inventaris_type');

                        if (!$model) {
                            return [];
                        }

                        $query = $model::query()->where('jumlah', '>', 0);

                        // Filter by gedung
                        if ($get('gedung_id')) {
                            $query->where('gedung_id', $get('gedung_id'));
                        }

                        // Filter by lantai
                        if ($get('lantai_id')) {
                            $query->where('lantai_id', $get('lantai_id'));
                        }

                        // Filter by ruang
                        if ($get('ruang_id')) {
                            $query->where('ruang_id', $get('ruang_id'));
                        }

                        // Filter by tipe aset
                        if ($model === DanaBos::class) {
                            if ($get('tipe_aset_dana_bos_id')) {
                                $query->where('tipe_aset_dana_bos_id', $get('tipe_aset_dana_bos_id'));
                            }
                        } else {
                            if ($get('tipe_aset_id')) {
                                $query->where('tipe_aset_id', $get('tipe_aset_id'));
                            }
                        }

                        return $query->get()
                            ->mapWithKeys(fn($item) => [
                                $item->id => $item->nama_barang . ' (' . $item->kode_inventaris . ')',
                            ])
                            ->toArray();
                    })
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $count = is_array($state) ? count($state) : 0;
                        $set('jumlah_rusak', $count);

                        if ($count > 0) {
                            $model = $get('inventaris_type');
                            if ($model) {
                                $firstId = is_array($state) ? $state[0] : $state;
                                $inventaris = $model::find($firstId);
                                $set('no_seri', $inventaris?->no_seri);
                            }
                        } else {
                            $set('no_seri', null);
                        }
                    }),

                TextInput::make('no_seri')
                    ->label('Nomor Seri')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('jumlah_rusak')
                    ->label('Jumlah Rusak')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->readOnly()
                    ->default(0),

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
