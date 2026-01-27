<?php

namespace App\Filament\Widgets;

use App\Models\DanaBos;
use App\Models\TipeAsetDanaBos;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\Summarizers\Sum;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class TipeAsetDanaBosWidget extends TableWidget
{
    protected static ?string $heading = 'Data Dana BOS';
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(DanaBos::query()->with(['tipeAsetDanaBos', 'ruang', 'lantai']))
            ->columns([
                Tables\Columns\TextColumn::make('tipeAsetDanaBos.tipe_aset_dana_bos')
                    ->label('Tipe Aset Dana BOS')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('ruang.ruang')
                    ->label('Ruang')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('lantai.lantai')
                    ->label('Lantai')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->summarize([
                        Sum::make()->label('Total')
                    ]),
            ])
            ->filters([
                SelectFilter::make('tipe_aset_dana_bos_id')
                    ->label('Filter Tipe Aset Dana BOS')
                    ->options(
                        TipeAsetDanaBos::pluck('tipe_aset_dana_bos', 'id')
                    )
                    ->placeholder('Semua'),

                // Filter Nama Barang
                Filter::make('nama_barang')
                    ->label('Nama Barang')
                    ->form([
                        TextInput::make('nama_barang')->placeholder('Cari nama barang...'),
                    ])
                    ->query(function ($query, array $data) {
                        if (! $data['nama_barang']) return $query;

                        return $query->where('dana_bos.nama_barang', 'like', '%' . $data['nama_barang'] . '%');
                    }),

                // Filter Ruang
                Filter::make('ruang')
                    ->label('Ruang')
                    ->form([
                        TextInput::make('ruang')->placeholder('Cari ruang...'),
                    ])
                    ->query(function ($query, array $data) {
                        if (! $data['ruang']) return $query;

                        return $query->whereHas('ruang', fn ($q) =>
                            $q->where('ruang', 'like', '%' . $data['ruang'] . '%')
                        );
                    }),

                // Filter Lantai
                Filter::make('lantai')
                    ->label('Lantai')
                    ->form([
                        TextInput::make('lantai')->placeholder('Cari lantai...'),
                    ])
                    ->query(function ($query, array $data) {
                        if (! $data['lantai']) return $query;

                        return $query->whereHas('lantai', fn ($q) =>
                            $q->where('lantai', 'like', '%' . $data['lantai'] . '%')
                        );
                    }),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make('danabos_custom')
                            ->withColumns([
                                Column::make('tipeAsetDanaBos.tipe_aset_dana_bos')->heading('Tipe Aset Dana BOS'),
                                Column::make('nama_barang')->heading('Nama Barang'),
                                Column::make('ruang.ruang')->heading('Ruang'),
                                Column::make('lantai.lantai')->heading('Lantai'),
                                Column::make('jumlah')->heading('Jumlah'),
                            ])
                            ->withFilename('danabos-' . now()->format('Y-m-d'))
                            ->fromTable()
                            ->withChunkSize(500),
                    ]),
            ]);
    }
}
