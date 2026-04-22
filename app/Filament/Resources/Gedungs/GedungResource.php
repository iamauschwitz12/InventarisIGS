<?php

namespace App\Filament\Resources\Gedungs;

use App\Filament\Resources\Gedungs\Pages\CreateGedung;
use App\Filament\Resources\Gedungs\Pages\EditGedung;
use App\Filament\Resources\Gedungs\Pages\ListGedungs;
use App\Filament\Resources\Gedungs\Schemas\GedungForm;
use App\Filament\Resources\Gedungs\Tables\GedungsTable;
use App\Models\Gedung;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GedungResource extends Resource
{
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->role === 'administrator';
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->role === 'administrator';
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->role === 'administrator';
    }
    protected static ?string $model = Gedung::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $recordTitleAttribute = 'nama_gedung';

    protected static string | UnitEnum | null $navigationGroup = 'Kategori Manajemen';

    public static function form(Schema $schema): Schema
    {
        return GedungForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GedungsTable::configure($table);
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
            'index' => ListGedungs::route('/'),
            'create' => CreateGedung::route('/create'),
            'edit' => EditGedung::route('/{record}/edit'),
        ];
    }
}
