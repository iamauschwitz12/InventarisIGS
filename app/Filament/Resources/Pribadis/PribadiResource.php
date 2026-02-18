<?php

namespace App\Filament\Resources\Pribadis;

use App\Filament\Resources\Pribadis\Pages\CreatePribadi;
use App\Filament\Resources\Pribadis\Pages\EditPribadi;
use App\Filament\Resources\Pribadis\Pages\ListPribadis;
use App\Filament\Resources\Pribadis\Schemas\PribadiForm;
use App\Filament\Resources\Pribadis\Tables\PribadisTable;
use App\Models\Pribadi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PribadiResource extends Resource
{
    protected static ?string $model = Pribadi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $recordTitleAttribute = 'Pribadi';

    protected static string | UnitEnum | null $navigationGroup = 'Menu inventaris';

    public static ?string $label = 'Inventaris Pribadi';

    public static function form(Schema $schema): Schema
    {
        return PribadiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PribadisTable::configure($table);
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
            'index' => ListPribadis::route('/'),
            'create' => CreatePribadi::route('/create'),
            'edit' => EditPribadi::route('/{record}/edit'),
        ];
    }
}
