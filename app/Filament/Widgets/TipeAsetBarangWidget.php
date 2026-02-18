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
            ->query(
                Marger::query()
                    ->whereRaw("CONVERT(marger_view.sumber USING utf8mb4) COLLATE utf8mb4_unicode_ci != ?", ['Dana BOS'])
            ) // ambil dari view gabungan, exclude Dana BOS
            ->columns([
                Tables\Columns\TextColumn::make('tipe_aset')->label('Jenis Aset')->sortable(),
                Tables\Columns\TextColumn::make('gedung')->label('Gedung')->sortable(),
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

                Tables\Filters\SelectFilter::make('gedung')
                    ->label('Filter Gedung')
                    ->options(\App\Models\Gedung::pluck('nama_gedung', 'nama_gedung'))
                    ->searchable()
                    ->placeholder('Semua Gedung')
                    ->query(function ($query, array $data) {
                        if (!$data['value'])
                            return $query;

                        return $query->where('marger_view.gedung', $data['value']);
                    }),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make('tipeasete_custom')
                            ->withColumns([
                                Column::make('tipe_aset')->heading('Tipe Aset'),
                                Column::make('gedung')->heading('Gedung'),
                                Column::make('nama_barang')->heading('Nama Barang'),
                                Column::make('ruang')->heading('Ruang'),
                                Column::make('lantai')->heading('Lantai'),
                                Column::make('jumlah')->heading('Jumlah'),
                                Column::make('sumber')->heading('Sumber Inventaris'),
                            ])
                            ->withFilename('tipeaset-' . now()->format('Y-m-d'))
                            ->fromTable()
                            ->withChunkSize(500),
                    ]),
            ])
            ->defaultSort('tipe_aset', 'asc');
    }
}
