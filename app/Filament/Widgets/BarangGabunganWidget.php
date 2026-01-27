<?php

namespace App\Filament\Widgets;

use App\Models\Marger;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\Summarizers\Sum;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class BarangGabunganWidget extends TableWidget
{
    protected static ?int $sort = 1;
    protected static ?string $heading = 'Barang Gabungan (Pribadi & Sekolah)';

    public function table(Table $table): Table
    {
        return $table
            ->query(Marger::query()
                ->whereRaw("CONVERT(marger_view.sumber USING utf8mb4) COLLATE utf8mb4_unicode_ci != ?", ['Dana BOS'])
            ) // exclude Dana BOS
            ->columns([
                Tables\Columns\TextColumn::make('nama_barang')->label('Nama Barang')->searchable(),
                Tables\Columns\TextColumn::make('ruang')->searchable(),
                Tables\Columns\TextColumn::make('lantai'),
                Tables\Columns\TextColumn::make('jumlah'),
                Tables\Columns\TextColumn::make('sumber')->label('Inventaris')->badge(),
                Tables\Columns\TextColumn::make('jumlah')
                ->label('Jumlah')
                ->summarize([
                Sum::make()->label('Total')
                ]),
            ])
            ->filters([
                // Filter sumber
                SelectFilter::make('sumber')
                    ->label('Asal Data')
                    ->options([
                        'Sekolah' => 'Sekolah',
                        'Pribadi'  => 'Pribadi',
                    ])
                    ->placeholder('Semua')
                    ->query(function ($query, $data) {
                    if (! $data['value']) return $query;

                    return $query->whereRaw(
                        "CONVERT(marger_view.sumber USING utf8mb4) COLLATE utf8mb4_unicode_ci = ?",
                        [$data['value']]
                    );
                }),

                // Nama barang
                Filter::make('nama_barang')
                    ->label('Nama Barang')
                    ->form([
                        TextInput::make('nama_barang')->placeholder('Cari nama barang...'),
                    ])
                    ->query(function ($query, array $data) {
                        if (! $data['nama_barang']) return $query;

                        return $query->where('marger_view.nama_barang', 'like', '%' . $data['nama_barang'] . '%');
                    }),

                // Ruang
                Filter::make('ruang')
                    ->label('Ruang')
                    ->form([
                        TextInput::make('ruang')->placeholder('Cari ruang...'),
                    ])
                    ->query(function ($query, array $data) {
                        if (! $data['ruang']) return $query;

                        return $query->where('marger_view.ruang', 'like', '%' . $data['ruang'] . '%');
                    }),

                // Lantai
                Filter::make('lantai')
                    ->label('Lantai')
                    ->form([
                        TextInput::make('lantai')->placeholder('Cari lantai...'),
                    ])
                    ->query(function ($query, array $data) {
                        if (! $data['lantai']) return $query;

                        return $query->where('marger_view.lantai', 'like', '%' . $data['lantai'] . '%');
                    }),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make('baranggabungan_custom')
                            ->withColumns([
                                Column::make('nama_barang')->heading('Nama Barang'),
                                Column::make('ruang')->heading('Ruang'),
                                Column::make('lantai')->heading('Lantai'),
                                Column::make('jumlah')->heading('Jumlah'),
                                Column::make('sumber')->heading('Sumber Inventaris'),
                            ])
                            ->withFilename('barang-gabungan-' . now()->format('Y-m-d'))
                            ->fromTable()
                            ->withChunkSize(500),
                    ]),
            ]);
    }
}
