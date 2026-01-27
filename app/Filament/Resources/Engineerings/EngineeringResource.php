<?php

namespace App\Filament\Resources\Engineerings;

use App\Filament\Resources\Engineerings\Pages\CreateEngineering;
use App\Filament\Resources\Engineerings\Pages\EditEngineering;
use App\Filament\Resources\Engineerings\Pages\ListEngineerings;
use App\Filament\Resources\Engineerings\Schemas\EngineeringForm;
use App\Filament\Resources\Engineerings\Tables\EngineeringsTable;
use App\Models\Engineering;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EngineeringResource extends Resource
{
    protected static ?string $model = Engineering::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrench;

    protected static ?string $recordTitleAttribute = 'Engineering';

    public static function form(Schema $schema): Schema
    {
        return EngineeringForm::configure($schema);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function table(Table $table): Table
    {
        return EngineeringsTable::configure($table);
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
            'index' => ListEngineerings::route('/'),
            'create' => CreateEngineering::route('/create'),
            'edit' => EditEngineering::route('/{record}/edit'),
        ];
    }
}
