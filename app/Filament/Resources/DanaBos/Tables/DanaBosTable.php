<?php

namespace App\Filament\Resources\DanaBos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms;
use App\Models\DanaBos;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\TipeAsetDanaBos;


class DanaBosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_barang')
                ->label('Nama Barang')
                ->searchable()
                ->sortable(),
                TextColumn::make('no_seri')
                ->label('Nomor Seri')
                ->searchable()
                ->sortable(),
                TextColumn::make('ruang.ruang')
                ->label('Ruang')
                ->searchable()
                ->sortable(),
                TextColumn::make('lantai.lantai')
                ->label('Lantai')
                ->searchable()
                ->sortable(),
                ImageColumn::make('img')
                ->label('Gambar')
                ->imageHeight(40)
                ->circular(),
                ImageColumn::make('qrcode')
                ->label('QR')
                ->disk('public')
                ->size(80),
                TextColumn::make('keterangan')
                ->label('Keterangan')
                ->searchable()
                ->sortable(),
                TextColumn::make('tipeAsetDanaBos.tipe_aset_dana_bos')
                ->label('Tipe Aset')
                ->searchable()
                ->sortable(),
                TextColumn::make('jumlah')
                ->label('Jumlah')
                ->summarize([
                Sum::make()->label('Total')
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make('danabos_custom')
                            // kita tidak pakai fromTable() di sini, tetapi bisa juga kombinasikan
                            ->withColumns([
                                Column::make('nama_barang')->heading('Nama Barang'),
                                Column::make('ruang.ruang')->heading('Ruang'),
                                Column::make('lantai.lantai')->heading('Lantai'),
                                Column::make('tipeAsetDanaBos.tipe_aset_dana_bos')->heading('Tipe Aset'),
                                // contoh format angka (currency) — gunakan NumberFormat jika perlu
                                Column::make('harga')
                                    ->heading('Harga')
                                    ->format(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1),
                                Column::make('tgl_beli')->heading('Tanggal Beli'),
                                Column::make('keterangan')->heading('Keterangan'),
                                Column::make('jumlah')->heading('Jumlah'),
                            ])
                            ->withFilename('danabos-' . now()->format('Y-m-d'))
                            ->fromTable() // jika mau sumber datanya tetap berasal dari table query (filter/search ikut)
                            ->withChunkSize(500),
                    ]),
            ])
            ->filters([
                Filter::make('filter_barang')
                    ->label('Filter Barang')
                    ->form([
                        Forms\Components\TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->placeholder('Cari nama barang...'),

                        Forms\Components\TextInput::make('ruang')
                            ->label('Ruang')
                            ->placeholder('Cari ruang...'),

                        Forms\Components\TextInput::make('lantai')
                            ->label('Lantai')
                            ->placeholder('Cari lantai...'),

                        Forms\Components\TextInput::make('no_seri')
                            ->label('Nomor Seri')
                            ->placeholder('Cari nomor seri...'),

                        Forms\Components\Select::make('tipe_aset_dana_bos_id')
                            ->label('Tipe Aset')
                            ->relationship('tipeAsetDanaBos', 'tipe_aset_dana_bos')
                            ->searchable()
                            ->preload()
                            ->placeholder('Cari tipe aset...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['nama_barang'] ?? null, fn ($q, $value) =>
                                $q->where('nama_barang', 'like', "%{$value}%")
                            )
                            ->when($data['ruang'] ?? null, fn ($q, $value) =>
                                $q->whereHas('ruang', fn ($qr) =>
                                    $qr->where('ruang', 'like', "%{$value}%")
                                )
                            )
                            ->when($data['lantai'] ?? null, fn ($q, $value) =>
                                $q->whereHas('lantai', fn ($ql) =>
                                    $ql->where('lantai', 'like', "%{$value}%")
                                )
                            )
                            ->when($data['no_seri'] ?? null, fn ($q, $value) =>
                                $q->where('no_seri', 'like', "%{$value}%")
                            )
                            ->when($data['tipe_aset_dana_bos_id'] ?? null, fn ($q, $value) =>
                                $q->where('tipe_aset_dana_bos_id', $value)
                            );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                \Filament\Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->url(fn ($record) => route('danabos.danabosqr', $record))
                ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
