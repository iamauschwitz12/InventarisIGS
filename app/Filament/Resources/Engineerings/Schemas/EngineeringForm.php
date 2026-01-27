<?php

namespace App\Filament\Resources\Engineerings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use App\Models\BarangRusak;


class EngineeringForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('barang_rusak_id')
                    ->label('Barang Rusak')
                    ->options(fn () =>
                        \App\Models\BarangRusak::with(['inventaris', 'lantai', 'ruang'])
                            ->latest()
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($barangRusak) => 
                                [$barangRusak->id => "{$barangRusak->inventaris?->nama_barang} (Seri: {$barangRusak->no_seri}) - Lantai: {$barangRusak->lantai?->lantai} | Ruang: {$barangRusak->ruang?->ruang}"]
                            )
                    )
                    ->getSearchResultsUsing(fn (string $search) => 
                        \App\Models\BarangRusak::with(['inventaris', 'lantai', 'ruang'])
                            ->where(function ($query) use ($search) {
                                $query->where('no_seri', 'like', "%{$search}%")
                                    ->orWhereHas('inventaris', fn ($q) =>
                                        $q->where('nama_barang', 'like', "%{$search}%")
                                    )
                                    ->orWhereHas('lantai', fn ($q) =>
                                        $q->where('lantai', 'like', "%{$search}%")
                                    )
                                    ->orWhereHas('ruang', fn ($q) =>
                                        $q->where('ruang', 'like', "%{$search}%")
                                    );
                            })
                            ->latest()
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($barangRusak) => 
                                [$barangRusak->id => "{$barangRusak->inventaris?->nama_barang} (Seri: {$barangRusak->no_seri}) - Lantai: {$barangRusak->lantai?->lantai} | Ruang: {$barangRusak->ruang?->ruang}"]
                            )
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                        "{$record->inventaris?->nama_barang} (Seri: {$record->no_seri}) - Lantai: {$record->lantai?->lantai} | Ruang: {$record->ruang?->ruang}"
                    )
                    ->preload()
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->label('Status Perbaikan')
                    ->options([
                        'sedang_diperiksa' => 'Sedang di Periksa',
                        'menunggu_antrian' => 'Menunggu Antrian',
                        'sedang_diperbaiki' => 'Sedang di Perbaiki',
                        'telah_diperbaiki' => 'Telah di Perbaiki',
                    ])
                    ->required()
                    ->native(false),

                DatePicker::make('tgl_mulai_perbaikan')
                    ->label('Tanggal Mulai'),

                DatePicker::make('tgl_selesai_perbaikan')
                    ->label('Tanggal Perkiraan Selesai'),

                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
