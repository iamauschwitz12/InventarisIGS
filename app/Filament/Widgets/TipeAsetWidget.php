<?php

namespace App\Filament\Widgets;

use App\Models\Marger;
use App\Models\TipeAset;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class TipeAsetWidget extends TableWidget
{
    protected static ?string $heading = 'Daftar Tipe Aset';
    protected static ?int $sort = 2; // supaya muncul di samping BarangGabunganWidget

    public function table(Table $table): Table
    {
        return $table
            ->query(TipeAset::query()
                ->withCount(['pribadis', 'sekolahs', 'danaBos']) // relasi ke tabel barang
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

                Tables\Columns\TextColumn::make('dana_bos_count')
                    ->label('Dana BOS')
                    ->counts('danaBos'),

                Tables\Columns\TextColumn::make('total_count')
                    ->label('Total')
                    ->getStateUsing(fn($record) => 
                        ($record->pribadis_count ?? 0) + 
                        ($record->sekolahs_count ?? 0) + 
                        ($record->dana_bos_count ?? 0)
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id')
                    ->label('Filter Tipe Aset')
                    ->options(
                        \App\Models\TipeAset::pluck('tipe_aset', 'id')
                    )
                    ->placeholder('Semua'),
            ]);
    }
}