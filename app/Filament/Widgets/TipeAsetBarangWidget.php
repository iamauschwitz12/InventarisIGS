<?php

namespace App\Filament\Widgets;

use App\Models\Marger;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\Summarizers\Sum;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class TipeAsetBarangWidget extends TableWidget
{
    protected static ?string $heading = 'Barang per Tipe Aset';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Marger::query()) // ambil dari view gabungan
            ->columns([
                Tables\Columns\TextColumn::make('tipe_aset')->label('Jenis Aset')->sortable(),
                Tables\Columns\TextColumn::make('nama_barang')->label('Nama Barang')->searchable(),
                Tables\Columns\TextColumn::make('ruang')->label('Ruang')->searchable(),
                Tables\Columns\TextColumn::make('lantai')->label('Lantai')->searchable(),
                Tables\Columns\TextColumn::make('jumlah')->label('Jumlah'),
                Tables\Columns\TextColumn::make('sumber')->label('Inventaris')->badge(),
                Tables\Columns\TextColumn::make('jumlah')
                ->label('Jumlah')
                ->summarize([
                Sum::make()->label('Total')
                ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe_aset_id')
                    ->label('Filter Tipe Aset')
                    ->options(\App\Models\TipeAset::pluck('tipe_aset', 'id'))
                    ->placeholder('Semua'),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make('tipeasete_custom')
                            // kita tidak pakai fromTable() di sini, tetapi bisa juga kombinasikan
                            ->withColumns([
                                Column::make('nama_barang')->heading('Nama Barang'),
                                Column::make('ruang.ruang')->heading('Ruang'),
                                Column::make('lantai.lantai')->heading('Lantai'),
                                Column::make('tipeAset.tipe_aset')->heading('Tipe Aset'),
                            ])
                            ->withFilename('tipeaset-' . now()->format('Y-m-d'))
                            ->fromTable() // jika mau sumber datanya tetap berasal dari table query (filter/search ikut)
                            ->withChunkSize(500),
                    ]),
            ])
            ->defaultSort('tipe_aset', 'asc');
    }
}
