<?php

namespace App\Filament\Resources\Engineerings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;

class EngineeringsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barangRusak.inventaris.nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('barangRusak.no_seri')
                    ->label('Nomor Seri')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('status')
                    ->label('Status Perbaikan')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'sedang_diperiksa' => 'Sedang di Periksa',
                        'menunggu_antrian' => 'Menunggu Antrian',
                        'sedang_diperbaiki' => 'Sedang di Perbaiki',
                        'telah_diperbaiki' => 'Telah di Perbaiki',
                        default => '-',
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'sedang_diperiksa' => 'info',
                        'menunggu_antrian' => 'warning',
                        'sedang_diperbaiki' => 'warning',
                        'telah_diperbaiki' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('tgl_mulai_perbaikan')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('tgl_selesai_perbaikan')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('filter_engineering')
                    ->label('Filter Engineering')
                    ->form([
                        Forms\Components\TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->placeholder('Cari nama barang...'),
                        
                        Forms\Components\TextInput::make('no_seri')
                            ->label('Nomor Seri')
                            ->placeholder('Cari nomor seri...'),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status Perbaikan')
                            ->options([
                                'sedang_diperiksa' => 'Sedang di Periksa',
                                'menunggu_antrian' => 'Menunggu Antrian',
                                'sedang_diperbaiki' => 'Sedang di Perbaiki',
                                'telah_diperbaiki' => 'Telah di Perbaiki',
                            ])
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih status...'),
                        
                        Forms\Components\DatePicker::make('tgl_mulai_from')
                            ->label('Tanggal Mulai Dari'),
                        
                        Forms\Components\DatePicker::make('tgl_mulai_to')
                            ->label('Tanggal Mulai Sampai'),
                        
                        Forms\Components\DatePicker::make('tgl_selesai_from')
                            ->label('Tanggal Selesai Dari'),
                        
                        Forms\Components\DatePicker::make('tgl_selesai_to')
                            ->label('Tanggal Selesai Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['nama_barang'] ?? null, fn ($q, $value) =>
                                $q->whereHas('barangRusak.inventaris', fn ($subq) =>
                                    $subq->where('nama_barang', 'like', "%{$value}%")
                                )
                            )
                            ->when($data['no_seri'] ?? null, fn ($q, $value) =>
                                $q->whereHas('barangRusak', fn ($subq) =>
                                    $subq->where('no_seri', 'like', "%{$value}%")
                                )
                            )
                            ->when($data['status'] ?? null, fn ($q, $value) =>
                                $q->where('status', $value)
                            )
                            ->when($data['tgl_mulai_from'] ?? null, fn ($q, $value) =>
                                $q->whereDate('tgl_mulai_perbaikan', '>=', $value)
                            )
                            ->when($data['tgl_mulai_to'] ?? null, fn ($q, $value) =>
                                $q->whereDate('tgl_mulai_perbaikan', '<=', $value)
                            )
                            ->when($data['tgl_selesai_from'] ?? null, fn ($q, $value) =>
                                $q->whereDate('tgl_selesai_perbaikan', '>=', $value)
                            )
                            ->when($data['tgl_selesai_to'] ?? null, fn ($q, $value) =>
                                $q->whereDate('tgl_selesai_perbaikan', '<=', $value)
                            );
                    }),
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
