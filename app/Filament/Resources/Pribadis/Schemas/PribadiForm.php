<?php

namespace App\Filament\Resources\Pribadis\Schemas;

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
use Illuminate\Database\Eloquent\Builder;

class PribadiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('no_invoice')
                    ->required()
                    ->placeholder('Masukan No Invoice')
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),
                TextInput::make('nama_barang')
                    ->required()
                    ->placeholder('Masukan Nama Barang')
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),
                Select::make('gedung_id')
                    ->relationship('gedung', 'nama_gedung')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive() // penting
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set('lantai_id', null);
                        $set('ruang_id', null);
                        self::updateKodeInventaris($get, $set);
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama_gedung')->required(),
                        Forms\Components\TextInput::make('alamat')->required(),
                    ]),
                Select::make('tipe_aset_id')
                    ->relationship('tipeAset', 'tipe_aset')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive() // penting
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        self::updateKodeInventaris($get, $set);
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('tipe_aset')->required(),
                    ]),
                Select::make('tipe_aset_kategori_id')
                    ->relationship('tipeAsetKategori', 'nama_tipe_kategori', modifyQueryUsing: function (Builder $query, $get) {
                        return $query->where('tipe_aset_id', $get('tipe_aset_id'));
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive() // penting
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        self::updateKodeInventaris($get, $set);
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama_tipe_kategori')->required(),
                    ]),
                Select::make('jenjang')
                    ->options([
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        self::updateKodeInventaris($get, $set);
                    }),
                TextInput::make('kode_inventaris')
                    ->required()
                    ->placeholder('Kode Inventaris Barang otomatis terisi')
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('no_seri')
                    ->required()
                    ->placeholder('Masukan nomor seri barang')
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),

                Select::make('lantai_id')
                    ->relationship('lantai', 'lantai', modifyQueryUsing: function (Builder $query, callable $get) {
                        $gedungId = $get('gedung_id');
                        if ($gedungId) {
                            $query->where('gedung_id', $gedungId);
                        }
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn(callable $set) => $set('ruang_id', null)),
                Select::make('ruang_id')
                    ->relationship('ruang', 'ruang', modifyQueryUsing: function (Builder $query, callable $get) {
                        $lantaiId = $get('lantai_id');
                        $gedungId = $get('gedung_id');

                        if ($gedungId) {
                            $query->where('gedung_id', $gedungId);
                        }
                        if ($lantaiId) {
                            $query->where('lantai_id', $lantaiId);
                        }
                        return $query;
                    })
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
                TextInput::make('jumlah')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->minValue(1)
                    ->helperText('Jumlah barang yang akan dibuat. Setiap barang akan mendapat kode inventaris unik.')
                    ->hiddenOn('edit'),
                TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->placeholder('Input keterangan atau kosongkan'),
                FileUpload::make('img')
                    ->label('Gambar')
                    ->disk('public')
                    ->directory('pribadi-images')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('250')
                    ->openable()
            ]);
    }

    protected static function updateKodeInventaris(callable $get, callable $set)
    {
        $gedungId = $get('gedung_id');
        $jenjang = $get('jenjang');
        $tipeAsetId = $get('tipe_aset_id');
        $kategoriId = $get('tipe_aset_kategori_id');

        $gedung = $gedungId ? \App\Models\Gedung::find($gedungId) : null;
        $tipeAset = $tipeAsetId ? \App\Models\TipeAset::find($tipeAsetId) : null;
        $tipeAsetKategori = $kategoriId ? \App\Models\TipeAsetKategori::find($kategoriId) : null;

        if ($gedung && $jenjang && $tipeAset) {
            // 1. Gedung: ambil 1 huruf dari kiri + tambahkan "P"
            $kode = strtoupper(substr($gedung->nama_gedung, 0, 1)) . 'P';

            // 2. Jenjang: konversi ke angka (SD=1, SMP=2, SMA=3)
            $jenjangMap = [
                'SD' => '1',
                'SMP' => '2',
                'SMA' => '3',
            ];
            $kode .= $jenjangMap[$jenjang] ?? '';

            // 3. Tipe Aset: konversi ke huruf (a, b, c, dst)
            $tipeAsetList = \App\Models\TipeAset::orderBy('id')->pluck('id')->toArray();
            $tipeAsetIndex = array_search($tipeAset->id, $tipeAsetList);
            if ($tipeAsetIndex !== false) {
                $kode .= '-' . chr(65 + $tipeAsetIndex); // 97 adalah ASCII code untuk 'a'
            }

            // 4. Tipe Aset Kategori: konversi ke angka berdasarkan urutan dalam tipe aset
            if ($tipeAsetKategori) {
                $kategoriList = \App\Models\TipeAsetKategori::where('tipe_aset_id', $tipeAset->id)
                    ->orderBy('id')
                    ->pluck('id')
                    ->toArray();

                $kategoriIndex = array_search($tipeAsetKategori->id, $kategoriList);

                if ($kategoriIndex !== false) {
                    $kode .= '-' . ($kategoriIndex + 1);
                }
            }

            $set('kode_inventaris', $kode);
        }
    }
}
