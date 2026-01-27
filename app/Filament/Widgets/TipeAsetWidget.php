<?php

namespace App\Filament\Widgets;

use App\Models\Marger;
use App\Models\TipeAset;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class TipeAsetWidget extends TableWidget
{
    protected static ?string $heading = 'Daftar Tipe Aset';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(TipeAset::query()
                ->withCount(['pribadis', 'sekolahs'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('tipe_aset')
                    ->label('Jenis Aset')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('pribadis_count')
                    ->label('Pribadi')
                    ->counts('pribadis'),

                Tables\Columns\TextColumn::make('sekolahs_count')
                    ->label('Sekolah')
                    ->counts('sekolahs'),

                Tables\Columns\TextColumn::make('total_count')
                    ->label('Total')
                    ->getStateUsing(fn($record) => 
                        ($record->pribadis_count ?? 0) + 
                        ($record->sekolahs_count ?? 0)
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id')
                    ->label('Filter Tipe Aset')
                    ->options(
                        \App\Models\TipeAset::pluck('tipe_aset', 'id')
                    )
                    ->placeholder('Semua'),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make('tipeaset_custom')
                            ->withColumns([
                                Column::make('tipe_aset')->heading('Jenis Aset'),
                                Column::make('pribadis_count')->heading('Pribadi'),
                                Column::make('sekolahs_count')->heading('Sekolah'),
                            ])
                            ->withFilename('tipeaset-' . now()->format('Y-m-d'))
                            ->fromTable()
                            ->withChunkSize(500),
                    ]),
            ]);
    }
}