<?php

namespace App\Filament\Resources\Sekolahs\Tables;

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
use App\Models\Sekolah;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Filament\Actions\Action;
use App\Models\PrintHistory;
use Filament\Support\Enums\Width;

class SekolahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('jumlah', '>', 0)
                    ->where(function (Builder $q) {
                        $q->whereRaw('id = (SELECT MIN(id) FROM sekolahs AS s2 WHERE s2.group_id = sekolahs.group_id AND s2.gedung_id = sekolahs.gedung_id AND s2.lantai_id = sekolahs.lantai_id AND s2.ruang_id = sekolahs.ruang_id AND s2.jumlah > 0)')
                            ->whereNotNull('group_id');
                    })->orWhere(function (Builder $q) {
                        $q->whereNull('group_id')->where('jumlah', '>', 0);
                    });
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
                    ->formatStateUsing(function (Sekolah $record) {
                        if ($record->group_id) {
                            $kode = $record->kode_inventaris;
                            $baseKode = preg_replace('/-\d{5}$/', '', $kode);
                            $count = Sekolah::where('group_id', $record->group_id)
                                ->where('gedung_id', $record->gedung_id)
                                ->where('lantai_id', $record->lantai_id)
                                ->where('ruang_id', $record->ruang_id)
                                ->where('jumlah', '>', 0)
                                ->count();
                            return $baseKode . ' (' . $count . ' item)';
                        }
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
                    ->getStateUsing(function (Sekolah $record) {
                        if ($record->group_id) {
                            return Sekolah::where('group_id', $record->group_id)
                                ->where('gedung_id', $record->gedung_id)
                                ->where('lantai_id', $record->lantai_id)
                                ->where('ruang_id', $record->ruang_id)
                                ->where('jumlah', '>', 0)
                                ->count();
                        }
                        return $record->jumlah;
                    }),
                ImageColumn::make('img')
                    ->label('Gambar')
                    ->imageHeight(40)
                    ->circular()
                    ->checkFileExistence(false)
                    ->getStateUsing(function (Sekolah $record) {
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
            ])
            ->headerActions([
                Action::make('history')
                    ->label('Riwayat Cetak')
                    ->modalHeading('Riwayat Cetak Anda')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn($action) => $action->label('Tutup'))
                    ->modalContent(function () {
                        $histories = PrintHistory::with(['sekolah.lantai', 'sekolah.ruang'])
                            ->where('user_id', auth()->id())
                            ->whereNotNull('sekolah_id')
                            ->latest()
                            ->get();

                        return view('filament.resources.sekolahs.components.print-history-modal', [
                            'records' => $histories,
                        ]);
                    })
                    ->modalWidth(Width::FiveExtraLarge),
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make('sekolahs_custom')
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
                                Column::make('harga')
                                    ->heading('Harga')
                                    ->format(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1),
                                Column::make('tgl_beli')->heading('Tanggal Beli'),
                                Column::make('keterangan')->heading('Keterangan'),
                                Column::make('jumlah')->heading('Jumlah'),
                            ])
                            ->withFilename('sekolahs-' . now()->format('Y-m-d'))
                            ->fromTable()
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

                        Forms\Components\TextInput::make('no_seri')
                            ->label('Nomor Seri')
                            ->placeholder('Cari nomor seri...'),

                        Forms\Components\TextInput::make('lantai')
                            ->label('Lantai')
                            ->placeholder('Cari lantai...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['nama_barang'] ?? null,
                                fn($q, $value) =>
                                $q->where('nama_barang', 'like', "%{$value}%")
                            )
                            ->when(
                                $data['ruang'] ?? null,
                                fn($q, $value) =>
                                $q->whereHas(
                                    'ruang',
                                    fn($qr) =>
                                    $qr->where('ruang', 'like', "%{$value}%")
                                )
                            )
                            ->when(
                                $data['no_seri'] ?? null,
                                fn($q, $value) =>
                                $q->where('no_seri', 'like', "%{$value}%")
                            )
                            ->when(
                                $data['lantai'] ?? null,
                                fn($q, $value) =>
                                $q->whereHas(
                                    'lantai',
                                    fn($ql) =>
                                    $ql->where('lantai', 'like', "%{$value}%")
                                )
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Detail Data Sekolah')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn($action) => $action->label('Tutup'))
                    ->modalContent(function (Sekolah $record) {
                        return view('filament.resources.sekolahs.components.view-record-modal', [
                            'record' => $record->load(['gedung', 'lantai', 'ruang', 'tipeAset', 'tipeAsetKategori']),
                        ]);
                    })
                    ->modalWidth(Width::FourExtraLarge),
                EditAction::make(),
                DeleteAction::make()
                    ->action(function (Sekolah $record) {
                        if ($record->group_id) {
                            Sekolah::where('group_id', $record->group_id)->delete();
                        } else {
                            $record->delete();
                        }
                    }),
                Action::make('lihat_barcode')
                    ->label('Lihat Barcode')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('Barcode Kode Inventaris')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn($action) => $action->label('Tutup'))
                    ->modalContent(function (Sekolah $record) {
                        if ($record->group_id) {
                            $records = Sekolah::where('group_id', $record->group_id)
                                ->where('jumlah', '>', 0)
                                ->orderBy('kode_inventaris')
                                ->get();
                            $groupId = $record->group_id;
                        } else {
                            $records = collect([$record]);
                            $groupId = null;
                        }

                        return view('filament.resources.sekolahs.components.barcode-group-modal', [
                            'records' => $records,
                            'groupId' => $groupId,
                        ]);
                    })
                    ->modalWidth(Width::FiveExtraLarge),
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(function (Sekolah $record) {
                        if ($record->group_id) {
                            PrintHistory::create([
                                'user_id' => auth()->id(),
                                'sekolah_id' => $record->id,
                                'start_number' => 1,
                                'jumlah' => Sekolah::where('group_id', $record->group_id)->count(),
                            ]);

                            return redirect()->route('sekolah.sekolahqr.group', [
                                'groupId' => $record->group_id,
                            ]);
                        } else {
                            PrintHistory::create([
                                'user_id' => auth()->id(),
                                'sekolah_id' => $record->id,
                                'start_number' => 1,
                                'jumlah' => $record->jumlah,
                            ]);

                            return redirect()->route('sekolah.sekolahqr', [
                                'record' => $record,
                                'start' => 1,
                            ]);
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
