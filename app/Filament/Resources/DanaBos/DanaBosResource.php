<?php

namespace App\Filament\Resources\DanaBos;

use App\Filament\Resources\DanaBos\Pages\CreateDanaBos;
use App\Filament\Resources\DanaBos\Pages\EditDanaBos;
use App\Filament\Resources\DanaBos\Pages\ListDanaBos;
use App\Filament\Resources\DanaBos\Schemas\DanaBosForm;
use App\Filament\Resources\DanaBos\Tables\DanaBosTable;
use App\Models\DanaBos;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DanaBosResource extends Resource
{
    protected static ?string $model = DanaBos::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'DanaBos';

    protected static string | UnitEnum | null $navigationGroup = 'Menu inventaris';

    public static ?string $label = 'Inventaris Dana Bos';

    public static function form(Schema $schema): Schema
    {
        return DanaBosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DanaBosTable::configure($table);
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
            'index' => ListDanaBos::route('/'),
            'create' => CreateDanaBos::route('/create'),
            'edit' => EditDanaBos::route('/{record}/edit'),
        ];
    }
}
