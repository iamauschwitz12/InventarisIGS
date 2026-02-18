<?php

namespace App\Filament\Resources\TransferInventaris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\Width;
use App\Models\TransferInventaris;
use App\Models\Pribadi;
use App\Models\Sekolah;
use App\Models\DanaBos;

class TransferInventarisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_transfer')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('jenis_inventaris')
                    ->label('Jenis Inventaris')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'pribadi' => 'Pribadi',
                        'sekolah' => 'Sekolah',
                        'dana_bos' => 'Dana BOS',
                        default => $state,
                    })
                    ->color(fn(string $state) => match ($state) {
                        'pribadi' => 'info',
                        'sekolah' => 'success',
                        'dana_bos' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('gedungAsal.nama_gedung')
                    ->label('Gedung Asal')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lantaiAsal.lantai')
                    ->label('Lantai Asal')
                    ->sortable(),
                TextColumn::make('ruangAsal.ruang')
                    ->label('Ruang Asal')
                    ->sortable(),
                TextColumn::make('gedungTujuan.nama_gedung')
                    ->label('Gedung Tujuan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lantaiTujuan.lantai')
                    ->label('Lantai Tujuan')
                    ->sortable(),
                TextColumn::make('ruangTujuan.ruang')
                    ->label('Ruang Tujuan')
                    ->sortable(),
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('jumlah_transfer')
                    ->label('Jumlah'),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('lihat_barcode')
                    ->label('Lihat Barcode')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('Barcode Transfer')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn($action) => $action->label('Tutup'))
                    ->modalContent(function (TransferInventaris $record) {
                        $jenis = $record->jenis_inventaris;
                        $sumberIds = is_array($record->sumber_id) ? $record->sumber_id : [$record->sumber_id];

                        $modelClass = match ($jenis) {
                            'pribadi' => Pribadi::class,
                            'sekolah' => Sekolah::class,
                            'dana_bos' => DanaBos::class,
                            default => null,
                        };

                        $records = collect();
                        if ($modelClass) {
                            $records = $modelClass::whereIn('id', $sumberIds)
                                ->orderBy('kode_inventaris')
                                ->get();
                        }

                        return view('filament.resources.transfer-inventaris.components.barcode-transfer-modal', [
                            'records' => $records,
                            'transfer' => $record->load(['gedungAsal', 'gedungTujuan']),
                            'jenis' => $jenis,
                        ]);
                    })
                    ->modalWidth(Width::FiveExtraLarge),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
