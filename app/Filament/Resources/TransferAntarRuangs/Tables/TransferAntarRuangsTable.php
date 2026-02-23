<?php

namespace App\Filament\Resources\TransferAntarRuangs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Pribadi;
use App\Models\Sekolah;
use App\Models\DanaBos;
use App\Models\TransferAntarRuang;

class TransferAntarRuangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(?string $state) => match ($state) {
                        'pinjam' => 'Pinjam',
                        'pengembalian' => 'Pengembalian',
                        default => $state ?? '-',
                    })
                    ->color(fn(?string $state) => match ($state) {
                        'pinjam' => 'info',
                        'pengembalian' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('tanggal_transfer')
                    ->label(fn($record) => match ($record?->status) {
                        'pinjam' => 'Tanggal Pinjam',
                        'pengembalian' => 'Tanggal Pengembalian',
                        default => 'Tanggal',
                    })
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
                TextColumn::make('gedung.nama_gedung')
                    ->label('Gedung')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lantaiAsal.lantai')
                    ->label('Lantai Asal')
                    ->sortable(),
                TextColumn::make('ruangAsal.ruang')
                    ->label('Ruang Asal')
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
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pinjam' => 'Pinjam',
                        'pengembalian' => 'Pengembalian',
                    ]),

                Filter::make('tanggal_transfer')
                    ->label('Tanggal')
                    ->form([
                        DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn(Builder $query, $date) => $query->whereDate('tanggal_transfer', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date) => $query->whereDate('tanggal_transfer', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari_tanggal'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['dari_tanggal'])->format('d/m/Y');
                        }
                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['sampai_tanggal'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),

                SelectFilter::make('jenis_inventaris')
                    ->label('Jenis Inventaris')
                    ->options([
                        'pribadi' => 'Pribadi',
                        'sekolah' => 'Sekolah',
                        'dana_bos' => 'Dana BOS',
                    ]),

                SelectFilter::make('gedung_id')
                    ->label('Gedung')
                    ->relationship('gedung', 'nama_gedung')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('lantai_asal_id')
                    ->label('Lantai Asal')
                    ->relationship('lantaiAsal', 'lantai')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('ruang_asal_id')
                    ->label('Ruang Asal')
                    ->relationship('ruangAsal', 'ruang')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('lantai_tujuan_id')
                    ->label('Lantai Tujuan')
                    ->relationship('lantaiTujuan', 'lantai')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('ruang_tujuan_id')
                    ->label('Ruang Tujuan')
                    ->relationship('ruangTujuan', 'ruang')
                    ->searchable()
                    ->preload(),

                Filter::make('nama_barang')
                    ->label('Nama Barang')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('nama_barang')
                            ->label('Cari Nama Barang')
                            ->placeholder('Ketik nama barang...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nama_barang'],
                            fn(Builder $query, $value) => $query->where('nama_barang', 'like', "%{$value}%"),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['nama_barang'] ?? null) {
                            return 'Nama Barang: ' . $data['nama_barang'];
                        }
                        return null;
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('lihat_barcode')
                    ->label('Lihat Barcode')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('Barcode Transfer Antar Ruang')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn($action) => $action->label('Tutup'))
                    ->modalContent(function (TransferAntarRuang $record) {
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

                        return view('filament.resources.transfer-antar-ruangs.components.barcode-transfer-modal', [
                            'records' => $records,
                            'transfer' => $record->load(['gedung', 'ruangAsal', 'ruangTujuan']),
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
