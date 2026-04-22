<?php

namespace App\Filament\Resources\TipeAsetKategoris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;

class TipeAsetKategorisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipeAset.tipe_aset')
                    ->label('Tipe Aset')
                    ->searchable(),
                TextColumn::make('nama_tipe_kategori')
                    ->label('Nama Tipe Kategori')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (): bool => auth()->user()?->role === 'administrator'),
                DeleteAction::make()
                    ->visible(fn (): bool => auth()->user()?->role === 'administrator'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->role === 'administrator'),
                ]),
            ])
            ->recordUrl(null);
    }
}
