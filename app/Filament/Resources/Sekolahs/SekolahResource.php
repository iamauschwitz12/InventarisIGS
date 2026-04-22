<?php

namespace App\Filament\Resources\Sekolahs;

use App\Filament\Resources\Sekolahs\Pages\CreateSekolah;
use App\Filament\Resources\Sekolahs\Pages\EditSekolah;
use App\Filament\Resources\Sekolahs\Pages\ListSekolahs;
use App\Filament\Resources\Sekolahs\Schemas\SekolahForm;
use App\Filament\Resources\Sekolahs\Tables\SekolahsTable;
use App\Models\Sekolah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SekolahResource extends Resource
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
    protected static ?string $model = Sekolah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $recordTitleAttribute = 'no_seri';

    protected static string | UnitEnum | null $navigationGroup = 'Menu inventaris';

    public static ?string $label = 'Inventaris Sekolah';

    public static function form(Schema $schema): Schema
    {
        return SekolahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SekolahsTable::configure($table);
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
            'index' => ListSekolahs::route('/'),
            'create' => CreateSekolah::route('/create'),
            'edit' => EditSekolah::route('/{record}/edit'),
        ];
    }
}
