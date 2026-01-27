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
use App\Models\TipeAsetDanaBos;

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
                TextColumn::make('tipe_aset_label')
                ->label('Tipe Aset')
                ->getStateUsing(function ($record) {
                    if ($record->inventaris_type === \App\Models\DanaBos::class && $record->inventaris) {
                        return $record->inventaris->tipeAsetDanaBos?->tipe_aset_dana_bos ?? '-';
                    }
                    return $record->tipeAset?->tipe_aset ?? '-';
                })
                ->sortable(),
                TextColumn::make('inventaris.nama_barang')
                ->label('Nama Barang')
                ->searchable(),
                TextColumn::make('no_seri')
                ->label('Nomor Seri')
                ->searchable()
                ->sortable(),
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
                Filter::make('filter_barang_rusak')
                    ->label('Filter Barang Rusak')
                    ->form([
                        Forms\Components\Select::make('inventaris_type')
                            ->label('Jenis Inventaris')
                            ->options([
                                \App\Models\Pribadi::class => 'Inventaris Pribadi',
                                \App\Models\Sekolah::class => 'Inventaris Sekolah',
                                \App\Models\DanaBos::class => 'Inventaris Dana BOS',
                            ])
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih jenis inventaris...'),

                        Forms\Components\TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->placeholder('Cari nama barang...'),

                        Forms\Components\TextInput::make('no_seri')
                            ->label('Nomor Seri')
                            ->placeholder('Cari nomor seri...'),

                        Forms\Components\Select::make('tipe_aset_id')
                            ->label('Tipe Aset')
                            ->options(function () {
                                $tipeAset = \App\Models\TipeAset::pluck('tipe_aset', 'id');
                                return $tipeAset;
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('Cari tipe aset...'),

                        Forms\Components\Select::make('tipe_aset_dana_bos_id')
                            ->label('Tipe Aset Dana BOS')
                            ->options(function () {
                                return \App\Models\TipeAsetDanaBos::pluck('tipe_aset_dana_bos', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('Cari tipe aset Dana BOS...'),

                        Forms\Components\Select::make('ruang_id')
                            ->label('Ruang')
                            ->relationship('ruang', 'ruang')
                            ->searchable()
                            ->preload()
                            ->placeholder('Cari ruang...'),

                        Forms\Components\Select::make('lantai_id')
                            ->label('Lantai')
                            ->relationship('lantai', 'lantai')
                            ->searchable()
                            ->preload()
                            ->placeholder('Cari lantai...'),

                        Forms\Components\DatePicker::make('tgl_rusak_from')
                            ->label('Tanggal Rusak Dari'),

                        Forms\Components\DatePicker::make('tgl_rusak_to')
                            ->label('Tanggal Rusak Sampai'),

                        Forms\Components\DatePicker::make('created_at_from')
                            ->label('Tanggal Input Dari'),

                        Forms\Components\DatePicker::make('created_at_to')
                            ->label('Tanggal Input Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['inventaris_type'] ?? null, fn ($q, $value) =>
                                $q->where('inventaris_type', $value)
                            )
                            ->when($data['nama_barang'] ?? null, fn ($q, $value) =>
                                $q->whereHas('inventaris', fn ($subq) =>
                                    $subq->where('nama_barang', 'like', "%{$value}%")
                                )
                            )
                            ->when($data['no_seri'] ?? null, fn ($q, $value) =>
                                $q->where('no_seri', 'like', "%{$value}%")
                            )
                            ->when($data['tipe_aset_id'] ?? null, fn ($q, $value) =>
                                $q->where('tipe_aset_id', $value)
                            )
                            ->when($data['tipe_aset_dana_bos_id'] ?? null, function ($q, $value) {
                                return $q->where(function ($query) use ($value) {
                                    $query->where('inventaris_type', \App\Models\DanaBos::class)
                                        ->whereRaw("EXISTS (
                                            SELECT 1 FROM dana_bos 
                                            WHERE barang_rusaks.inventaris_id = dana_bos.id 
                                            AND dana_bos.tipe_aset_dana_bos_id = ?
                                        )", [$value]);
                                });
                            })
                            ->when($data['ruang_id'] ?? null, fn ($q, $value) =>
                                $q->where('ruang_id', $value)
                            )
                            ->when($data['lantai_id'] ?? null, fn ($q, $value) =>
                                $q->where('lantai_id', $value)
                            )
                            ->when($data['tgl_rusak_from'] ?? null, fn ($q, $value) =>
                                $q->whereDate('tgl_rusak', '>=', $value)
                            )
                            ->when($data['tgl_rusak_to'] ?? null, fn ($q, $value) =>
                                $q->whereDate('tgl_rusak', '<=', $value)
                            )
                            ->when($data['created_at_from'] ?? null, fn ($q, $value) =>
                                $q->whereDate('created_at', '>=', $value)
                            )
                            ->when($data['created_at_to'] ?? null, fn ($q, $value) =>
                                $q->whereDate('created_at', '<=', $value)
                            );
                    }),
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
