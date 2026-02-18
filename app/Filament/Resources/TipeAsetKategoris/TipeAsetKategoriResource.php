<?php

namespace App\Filament\Resources\TipeAsetKategoris;

use App\Filament\Resources\TipeAsetKategoris\Pages\CreateTipeAsetKategori;
use App\Filament\Resources\TipeAsetKategoris\Pages\EditTipeAsetKategori;
use App\Filament\Resources\TipeAsetKategoris\Pages\ListTipeAsetKategoris;
use App\Filament\Resources\TipeAsetKategoris\Schemas\TipeAsetKategoriForm;
use App\Filament\Resources\TipeAsetKategoris\Tables\TipeAsetKategorisTable;
use App\Models\TipeAsetKategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TipeAsetKategoriResource extends Resource
{
    protected static ?string $model = TipeAsetKategori::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'TipeAsetKat';

    protected static string | UnitEnum | null $navigationGroup = 'Kategori Manajemen';

    public static function form(Schema $schema): Schema
    {
        return TipeAsetKategoriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipeAsetKategorisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTipeAsetKategoris::route('/'),
            'create' => CreateTipeAsetKategori::route('/create'),
            'edit' => EditTipeAsetKategori::route('/{record}/edit'),
        ];
    }
}
