<?php

namespace App\Filament\Resources\BarangRusaks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms;
use App\Models\Pribadi;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BarangRusaksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inventaris_type')
                ->label('Jenis Inventaris')
                ->formatStateUsing(fn ($state) => class_basename($state))
                ->searchable(),
                TextColumn::make('inventaris.nama_barang')
                ->label('Nama Barang')
                ->searchable(),
                TextColumn::make('jumlah_rusak')->label('Banyak Rusak'),
                TextColumn::make('ruang.ruang')
                ->label('Ruang')
                ->searchable()
                ->sortable(),
                TextColumn::make('lantai.lantai')
                ->label('Lantai')
                ->searchable()
                ->sortable(),
                TextColumn::make('tgl_rusak')->date()->label('Tanggal Rusak'),
                TextColumn::make('keterangan')->label('Keterangan')->limit(30)
                ->searchable(),
                TextColumn::make('created_at')->date()->label('Tgl Input'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make('barangrusak_custom')
                            // kita tidak pakai fromTable() di sini, tetapi bisa juga kombinasikan
                            ->withColumns([
                                Column::make('nama_barang')->heading('Nama Barang'),
                                Column::make('ruang.ruang')->heading('Ruang'),
                                Column::make('lantai.lantai')->heading('Lantai'),
                                Column::make('tipeAset.tipe_aset')->heading('Tipe Aset'),
                                Column::make('created_at')->heading('Tanggal Input'),
                                Column::make('tgl_rusak')->heading('Tanggal Rusak'),
                                Column::make('keterangan')->heading('Keterangan'),
                            ])
                            ->withFilename('barangrusak-' . now()->format('Y-m-d'))
                            ->fromTable() // jika mau sumber datanya tetap berasal dari table query (filter/search ikut)
                            ->withChunkSize(500),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
