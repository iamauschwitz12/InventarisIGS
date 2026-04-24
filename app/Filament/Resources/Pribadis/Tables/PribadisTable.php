<?php

namespace App\Filament\Resources\Pribadis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms;
use App\Models\Pribadi;
use App\Models\Ruang;
use App\Models\Lantai;
use App\Models\Gedung;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Filament\Actions\Action;
use App\Models\PrintHistory;
use Filament\Support\Enums\Width;


class PribadisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('jumlah', '>', 0);
            })
            ->columns([
                TextColumn::make('no_invoice')
                    ->label('No Invoice')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kode_inventaris')
                    ->label('Kode Inventaris')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function (Pribadi $record) {
                        return $record->kode_inventaris;
                    }),
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenjang')
                    ->label('Jenjang')
                    ->sortable(),
                TextColumn::make('gedung.nama_gedung')
                    ->label('Gedung')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tipeAset.tipe_aset')
                    ->label('Tipe Aset')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tipeAsetKategori.nama_tipe_kategori')
                    ->label('Kategori')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tgl_beli')
                    ->label('Tgl Beli')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->getStateUsing(function (Pribadi $record) {
                        return $record->jumlah;
                    })
                    ->summarize(Sum::make()->label('jumlah')),
                ImageColumn::make('img')
                    ->label('Gambar')
                    ->imageHeight(40)
                    ->circular()
                    ->checkFileExistence(false)
                    ->getStateUsing(function (Pribadi $record) {
                        if ($record->img) {
                            return asset('storage/' . $record->img);
                        }
                        return null;
                    }),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label('PIC (Input Oleh)')
                    ->icon('heroicon-o-user-circle')
                    ->badge()
                    ->color(fn ($record) => match ($record->user?->role) {
                        'administrator' => 'danger',
                        'operator'      => 'info',
                        default         => 'gray',
                    })
                    ->getStateUsing(fn ($record) => $record->user?->name ?? '-')
                    ->searchable(query: fn ($query, string $search) => $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")))
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('history')
                    ->label('Riwayat Cetak')
                    // ->icon('heroicon-o-clock')
                    ->modalHeading('Riwayat Cetak Anda')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn($action) => $action->label('Tutup'))
                    ->modalContent(function () {
                        $histories = PrintHistory::with(['pribadi.lantai', 'pribadi.ruang'])
                            ->where('user_id', auth()->id())
                            ->whereNotNull('pribadi_id')
                            ->latest()
                            ->get();

                        return view('filament.resources.pribadis.components.print-history-modal', [
                            'records' => $histories,
                        ]);
                    })
                    ->modalWidth(Width::FiveExtraLarge),
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make('pribadis_custom')
                            ->withColumns([
                                Column::make('no_invoice')->heading('No Invoice'),
                                Column::make('kode_inventaris')->heading('Kode Inventaris'),
                                Column::make('nama_barang')->heading('Nama Barang'),
                                Column::make('jenjang')->heading('Jenjang'),
                                Column::make('gedung.nama_gedung')->heading('Gedung'),
                                Column::make('tipeAset.tipe_aset')->heading('Tipe Aset'),
                                Column::make('tipeAsetKategori.nama_tipe_kategori')->heading('Kategori'),
                                Column::make('ruang.ruang')->heading('Ruang'),
                                Column::make('lantai.lantai')->heading('Lantai'),
                                Column::make('tipeAset.tipe_aset')->heading('Tipe Aset'),
                                Column::make('harga')
                                    ->heading('Harga')
                                    ->format(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1),
                                Column::make('tgl_beli')->heading('Tanggal Beli'),
                                Column::make('keterangan')->heading('Keterangan'),
                                Column::make('jumlah')->heading('Jumlah'),
                            ])
                            ->withFilename('pribadis-' . now()->format('Y-m-d'))
                            ->fromTable()
                            ->withChunkSize(500),
                    ]),
            ])
            ->filters([
                Filter::make('filter_barang')
                    ->label('Filter Barang')
                    ->form([
                        Forms\Components\Select::make('nama_barang')
                            ->label('Nama Barang')
                            ->placeholder('Pilih nama barang...')
                            ->searchable()
                            ->options(
                                fn() => Pribadi::where('jumlah', '>', 0)
                                    ->distinct()
                                    ->orderBy('nama_barang')
                                    ->pluck('nama_barang', 'nama_barang')
                                    ->toArray()
                            ),

                        Forms\Components\Select::make('gedung_id')
                            ->label('Gedung')
                            ->placeholder('Pilih gedung...')
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($set) {
                                $set('lantai_id', null);
                                $set('ruang_id', null);
                            })
                            ->options(
                                fn() => Gedung::orderBy('nama_gedung')
                                    ->pluck('nama_gedung', 'id')
                                    ->toArray()
                            ),

                        Forms\Components\Select::make('lantai_id')
                            ->label('Lantai')
                            ->placeholder('Pilih lantai...')
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn($set) => $set('ruang_id', null))
                            ->options(
                                fn($get) => Lantai::when(
                                    $get('gedung_id'),
                                    fn($q, $v) => $q->where('gedung_id', $v)
                                )
                                ->orderBy('lantai')
                                ->pluck('lantai', 'id')
                                ->toArray()
                            ),

                        Forms\Components\Select::make('ruang_id')
                            ->label('Ruang')
                            ->placeholder('Pilih ruang...')
                            ->searchable()
                            ->options(
                                fn($get) => Ruang::when(
                                    $get('lantai_id'),
                                    fn($q, $v) => $q->where('lantai_id', $v)
                                )
                                ->when(
                                    $get('gedung_id'),
                                    fn($q, $v) => $q->where('gedung_id', $v)
                                )
                                ->orderBy('ruang')
                                ->pluck('ruang', 'id')
                                ->toArray()
                            ),

                        Forms\Components\TextInput::make('no_seri')
                            ->label('Nomor Seri')
                            ->placeholder('Cari nomor seri...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['nama_barang'] ?? null,
                                fn($q, $value) =>
                                $q->where('nama_barang', $value)
                            )
                            ->when(
                                $data['gedung_id'] ?? null,
                                fn($q, $value) =>
                                $q->where('gedung_id', $value)
                            )
                            ->when(
                                $data['lantai_id'] ?? null,
                                fn($q, $value) =>
                                $q->where('lantai_id', $value)
                            )
                            ->when(
                                $data['ruang_id'] ?? null,
                                fn($q, $value) =>
                                $q->where('ruang_id', $value)
                            )
                            ->when(
                                $data['no_seri'] ?? null,
                                fn($q, $value) =>
                                $q->where('no_seri', 'like', "%{$value}%")
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Detail Data Pribadi')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn($action) => $action->label('Tutup'))
                    ->modalContent(function (Pribadi $record) {
                        return view('filament.resources.pribadis.components.view-record-modal', [
                            'record' => $record->load(['gedung', 'lantai', 'ruang', 'tipeAset', 'tipeAsetKategori']),
                        ]);
                    })
                    ->modalWidth(Width::FourExtraLarge),
                EditAction::make()
                    ->visible(fn (): bool => auth()->user()?->role === 'administrator'),
                DeleteAction::make()
                    ->visible(fn (): bool => auth()->user()?->role === 'administrator')
                    ->action(function (Pribadi $record) {
                        if ($record->group_id) {
                            Pribadi::where('group_id', $record->group_id)->delete();
                        } else {
                            $record->delete();
                        }
                    })
                    ->requiresConfirmation(),
                Action::make('lihat_barcode')
                    ->label('Lihat Barcode')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('Barcode Kode Inventaris')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn($action) => $action->label('Tutup'))
                    ->modalContent(function (Pribadi $record) {
                        return view('filament.resources.pribadis.components.barcode-group-modal', [
                            'records' => collect([$record]),
                            'groupId' => null,
                        ]);
                    })
                    ->modalWidth(Width::FiveExtraLarge),
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(function (Pribadi $record) {
                        PrintHistory::create([
                            'user_id' => auth()->id(),
                            'pribadi_id' => $record->id,
                            'start_number' => 1,
                            'jumlah' => 1,
                        ]);

                        return redirect()->route('pribadi.printqr', [
                            'record' => $record,
                            'start' => 1,
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->role === 'administrator'),
                    \Filament\Actions\BulkAction::make('print_bulk')
                        ->label('Print Barcode Terpilih')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $ids = $records->pluck('id')->join(',');

                            foreach ($records as $record) {
                                PrintHistory::create([
                                    'user_id' => auth()->id(),
                                    'pribadi_id' => $record->id,
                                    'start_number' => 1,
                                    'jumlah' => 1,
                                ]);
                            }

                            return redirect()->route('pribadi.printqr.bulk', ['ids' => $ids]);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->recordUrl(null);
    }
}
