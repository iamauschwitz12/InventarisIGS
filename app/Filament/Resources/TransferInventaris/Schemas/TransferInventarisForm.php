<?php

namespace App\Filament\Resources\TransferInventaris\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use App\Models\Pribadi;
use App\Models\Sekolah;
use App\Models\DanaBos;

use App\Models\PrintHistory;

class TransferInventarisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sumber Transfer')
                    ->schema([
                        Select::make('jenis_inventaris')
                            ->label('Jenis Inventaris')
                            ->options([
                                'pribadi' => 'Pribadi',
                                'sekolah' => 'Sekolah',
                                'dana_bos' => 'Dana BOS',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set) {
                                $set('gedung_asal_id', null);
                                $set('lantai_asal_id', null);
                                $set('ruang_asal_id', null);
                                $set('sumber_id', null);
                                $set('nama_barang', null);
                                $set('jumlah_tersedia', null);
                            }),

                        Select::make('gedung_asal_id')
                            ->label('Gedung Asal')
                            ->options(\App\Models\Gedung::pluck('nama_gedung', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->dehydrated()
                            ->afterStateUpdated(function (callable $set) {
                                $set('lantai_asal_id', null);
                                $set('ruang_asal_id', null);
                                $set('sumber_id', null);
                                $set('nama_barang', null);
                                $set('jumlah_tersedia', null);
                            }),

                        Select::make('lantai_asal_id')
                            ->label('Lantai Asal')
                            ->options(function (callable $get) {
                                $gedungId = $get('gedung_asal_id');
                                if (!$gedungId)
                                    return [];
                                return \App\Models\Lantai::where('gedung_id', $gedungId)->pluck('lantai', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->dehydrated()
                            ->afterStateUpdated(function (callable $set) {
                                $set('ruang_asal_id', null);
                                $set('sumber_id', null);
                                $set('nama_barang', null);
                                $set('jumlah_tersedia', null);
                            }),

                        Select::make('ruang_asal_id')
                            ->label('Ruang Asal')
                            ->options(function (callable $get) {
                                $lantaiId = $get('lantai_asal_id');
                                if (!$lantaiId)
                                    return [];
                                return \App\Models\Ruang::where('lantai_id', $lantaiId)->pluck('ruang', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->dehydrated()
                            ->afterStateUpdated(function (callable $set) {
                                $set('sumber_id', null);
                                $set('nama_barang', null);
                                $set('jumlah_tersedia', null);
                            }),

                        Select::make('sumber_id')
                            ->label('Pilih Barang')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->multiple()
                            ->reactive()
                            ->options(function (callable $get) {
                                $jenis = $get('jenis_inventaris');
                                $gedungId = $get('gedung_asal_id');
                                $lantaiId = $get('lantai_asal_id');
                                $ruangId = $get('ruang_asal_id');

                                if (!$jenis)
                                    return [];

                                $model = match ($jenis) {
                                    'pribadi' => Pribadi::class,
                                    'sekolah' => Sekolah::class,
                                    'dana_bos' => DanaBos::class,
                                    default => null,
                                };

                                if (!$model)
                                    return [];

                                $query = $model::where('jumlah', '>', 0);

                                if ($gedungId) {
                                    $query->where('gedung_id', $gedungId);
                                }
                                if ($lantaiId) {
                                    $query->where('lantai_id', $lantaiId);
                                }
                                if ($ruangId) {
                                    $query->where('ruang_id', $ruangId);
                                }

                                return $query->get()
                                    ->mapWithKeys(fn($item) => [
                                        $item->id => $item->nama_barang . ' (' . $item->kode_inventaris . ')',
                                    ])
                                    ->toArray();
                            })
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $count = is_array($state) ? count($state) : 0;
                                $set('jumlah_tersedia', $count);
                                $set('jumlah_transfer', $count);

                                if ($count > 0) {
                                    $jenis = $get('jenis_inventaris');
                                    $model = match ($jenis) {
                                        'pribadi' => Pribadi::class,
                                        'sekolah' => Sekolah::class,
                                        'dana_bos' => DanaBos::class,
                                        default => null,
                                    };

                                    if ($model) {
                                        $firstId = is_array($state) ? $state[0] : $state;
                                        $barang = $model::find($firstId);
                                        if ($barang) {
                                            $set('nama_barang', $barang->nama_barang);
                                        }
                                    }
                                } else {
                                    $set('nama_barang', null);
                                }
                            }),

                        TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('jumlah_tersedia')
                            ->label('Jumlah Dipilih')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Section::make('Tujuan Transfer')
                    ->schema([
                        Select::make('gedung_tujuan_id')
                            ->label('Gedung Tujuan')
                            ->options(\App\Models\Gedung::pluck('nama_gedung', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set) {
                                $set('lantai_tujuan_id', null);
                                $set('ruang_tujuan_id', null);
                            })
                            ->rules([
                                function (callable $get) {
                                    return function (string $attribute, $value, $fail) use ($get) {
                                        if ($get('gedung_asal_id') == $value) {
                                            $fail('Gedung tujuan tidak boleh sama dengan gedung asal.');
                                        }
                                    };
                                },
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('lantai_tujuan_id')
                                    ->label('Lantai Tujuan')
                                    ->options(function (callable $get) {
                                        $gedungId = $get('gedung_tujuan_id');
                                        if (!$gedungId) {
                                            return [];
                                        }
                                        return \App\Models\Lantai::where('gedung_id', $gedungId)->pluck('lantai', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(fn(callable $set) => $set('ruang_tujuan_id', null)),

                                Select::make('ruang_tujuan_id')
                                    ->label('Ruang Tujuan')
                                    ->options(function (callable $get) {
                                        $lantaiId = $get('lantai_tujuan_id');
                                        if (!$lantaiId) {
                                            return [];
                                        }
                                        return \App\Models\Ruang::where('lantai_id', $lantaiId)->pluck('ruang', 'id');
                                    })
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Section::make('Detail Transfer')
                    ->schema([
                        \Filament\Forms\Components\Hidden::make('jumlah_transfer')
                            ->default(0)
                            ->dehydrated(),

                        DatePicker::make('tanggal_transfer')
                            ->label('Tanggal Transfer')
                            ->required()
                            ->default(now()),

                        TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('Catatan transfer (opsional)'),
                    ]),
            ]);
    }
}
