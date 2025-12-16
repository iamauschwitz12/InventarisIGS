<?php

namespace App\Filament\Widgets;

use App\Models\Marger;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

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
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe_aset_id')
                    ->label('Filter Tipe Aset')
                    ->options(\App\Models\TipeAset::pluck('tipe_aset', 'id'))
                    ->placeholder('Semua'),
            ])
            ->defaultSort('tipe_aset', 'asc');
    }
}
