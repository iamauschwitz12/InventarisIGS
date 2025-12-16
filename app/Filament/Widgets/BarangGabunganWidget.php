<?php

namespace App\Filament\Widgets;

use App\Models\Marger;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget;

class BarangGabunganWidget extends TableWidget
{
    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(Marger::query())
            ->columns([
                Tables\Columns\TextColumn::make('nama_barang')->label('Nama Barang')->searchable(),
                Tables\Columns\TextColumn::make('ruang')->searchable(),
                Tables\Columns\TextColumn::make('lantai'),
                Tables\Columns\TextColumn::make('jumlah'),
                Tables\Columns\TextColumn::make('sumber')->label('Inventaris')->badge(),
            ])
            ->filters([
                // Filter sumber
                SelectFilter::make('sumber')
                    ->label('Asal Data')
                    ->options([
                        'Sekolah' => 'Sekolah',
                        'Pribadi'  => 'Pribadi',
                        'Dana BOS' => 'Dana BOS',
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
            ]);
    }
}
